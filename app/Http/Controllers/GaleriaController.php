<?php

namespace App\Http\Controllers;

use App\Models\Galeria;
use App\Models\GaleriaMidia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Str;

class GaleriaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Visualizar Galeria', only: ['indexAdmin']),
            new Middleware('permission:Criar Galeria', only: ['create', 'store']),
            new Middleware('permission:Editar Galeria', only: ['edit', 'update']),
            new Middleware('permission:Deletar Galeria', only: ['destroy'])
        ];
    }
    public function indexAdmin()
    {
        $anos = Galeria::selectRaw('YEAR(created_at) as ano')
            ->distinct()->orderBy('ano', 'desc')->pluck('ano');

        $midias = Galeria::latest()->paginate(12);

        return view('galeria.indexAdmin', compact('midias', 'anos'));
    }

    public function indexPublic()
    {
        $anos = Galeria::selectRaw('YEAR(created_at) as ano')
            ->distinct()->orderBy('ano', 'desc')->pluck('ano');

        $midias = Galeria::latest()->paginate(12);

        return view('galeria.index', compact('midias', 'anos'));
    }

    public function peloAno($ano)
    {
        $anos = Galeria::selectRaw('YEAR(created_at) as ano')
            ->distinct()->orderBy('ano', 'desc')->pluck('ano');

        $midias = Galeria::whereYear('created_at', $ano)->latest()->paginate(12);

        return view('galeria.index', compact('midias', 'anos', 'ano'));
    }

    public function create()
    {
        return view('galeria.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'data_evento' => 'required|date',
            'imagem_principal' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
            'descricao' => 'nullable|string',
            'tipo_midia' => 'required|string|in:imagem,video',
            'link_youtube' => ['required_if:tipo_midia,video', 'nullable', 'url', 'regex:#^(https?\:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/)[a-zA-Z0-9_-]+#'],
            'arquivos' => 'required_if:tipo_midia,imagem|nullable|array',
            'arquivos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'titulo.required' => 'O campo título é obrigatório.',
            'data_evento.required' => 'A data do evento é obrigatória.',
            'imagem_principal.required' => 'A imagem principal é obrigatória.',
            'imagem_principal.image' => 'O arquivo principal deve ser uma imagem.',
            'tipo_midia.required' => 'O tipo da mídia é obrigatório.',
            'link_youtube.required_if' => 'O link do YouTube é obrigatório quando o tipo é vídeo.',
            'link_youtube.regex' => 'O link deve ser uma URL válida do YouTube.',
            'arquivos.required_if' => 'A seleção de arquivos é obrigatória quando o tipo é Galeria de Imagens.',
            'arquivos.*.image' => 'Todos os arquivos selecionados devem ser imagens.',
        ]);

        DB::beginTransaction();
        try {
            $file = $request->file('imagem_principal');
            $mainImageName = Str::slug($request->titulo) . '-main-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img/fotos'), $mainImageName);
            $mainImagePath = 'img/fotos/' . $mainImageName;

            $galeria = Galeria::create([
                'titulo' => $request->titulo,
                'descricao' => $request->descricao,
                'data_evento' => $request->data_evento,
                'caminho' => $mainImagePath,
                'ano' => date('Y', strtotime($request->data_evento)),
                'tipo' => $request->tipo_midia
            ]);

            if ($request->tipo_midia === 'imagem' && $request->hasFile('arquivos')) {
                foreach ($request->file('arquivos') as $key => $mediaFile) {
                    $mediaFileName = Str::slug($request->titulo) . '-item-' . time() . '-' . ($key + 1) . '.' . $mediaFile->getClientOriginalExtension();
                    $mediaFile->move(public_path('img/fotos'), $mediaFileName);
                    $mediaPath = 'img/fotos/' . $mediaFileName;

                    GaleriaMidia::create([
                        'galeria_id' => $galeria->id,
                        'tipo' => 'imagem',
                        'caminho' => $mediaPath,
                    ]);
                }
            } elseif ($request->tipo_midia === 'video') {
                GaleriaMidia::create([
                    'galeria_id' => $galeria->id,
                    'tipo' => 'video',
                    'caminho' => $request->link_youtube,
                ]);
            }

            DB::commit();

            return redirect()->route('galeria.indexAdmin')->with('success', 'Evento cadastrado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            error_log("deu ruim: " . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro inesperado ao salvar o evento. Tente novamente.');
        }
    }

    public function edit($id)
    {
        $galeria = Galeria::findOrFail($id);
        return view('galeria.edit', compact('galeria'));
    }

    public function show(Galeria $galeria)
    {
        $galeria->load('midias');

        return view('galeria.show', compact('galeria'));
    }

    public function update(Request $request, $id)
    {
        $galeria = Galeria::findOrFail($id);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:255',
            'tipo' => 'required|string|in:imagem,video',
            'link' => ['required_if:tipo,video', 'nullable', 'url', 'regex:#^(https?\:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/)[a-zA-Z0-9_-]+#'],
            'file' => 'nullable|file|mimes:png,jpg,jpeg,gif|max:2048',
        ],[
            'titulo.required' => 'O campo título é obrigatório.',
            'titulo.max' => 'O campo título deve ter no máximo 255 caracteres.',
            'descricao.max' => 'O campo descrição deve ter no máximo 255 caracteres.',
            'file.mimes' => 'O arquivo deve ser uma imagem.',
            'file.max' => 'O arquivo deve ter no máximo 2MB.',
            'link.required_if' => 'O link do YouTube é obrigatório quando o tipo é vídeo.',
            'link.regex' => 'O link deve ser uma URL válida do YouTube.',
        ]);

        if ($request->tipo === 'video') {
            if ($galeria->tipo === 'imagem') {
                // Delete old image if switching from image to video
                $oldPath = public_path($galeria->caminho);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }
            $galeria->caminho = $request->link;
            $galeria->tipo = 'video';
        } else {
            if ($request->hasFile('file')) {
                // Delete old image if uploading new one
                if ($galeria->tipo === 'imagem') {
                    $oldPath = public_path($galeria->caminho);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }
                $file = $request->file('file');
                $fileName = date('YmdHis') . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('imagens/fotos'), $fileName);
                $galeria->caminho = 'imagens/fotos/' . $fileName;
                $galeria->tipo = 'imagem';
            }
        }

        $galeria->titulo = $request->titulo;
        $galeria->descricao = $request->descricao;
        $galeria->save();

        return redirect()->route('galeria.indexAdmin')->with('success', 'Mídia atualizada com sucesso!');
    }

    public function destroy($id)
    {
        try {
            $galeria = Galeria::findOrFail($id);

            if ($galeria->tipo === 'imagem') {
                $path = public_path($galeria->caminho);
                if (File::exists($path)) {
                    File::delete($path);
                }
            }

            $galeria->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'id' => $id
                ]);
            }

            return redirect()->route('galeria.indexAdmin');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir a mídia: ' . $e->getMessage()
            ], 500);
        }
    }

    private function isVideo($mimeType)
    {
        return in_array($mimeType, [
            'video/mp4',
            'video/quicktime',
            'video/x-msvideo',
        ]);
    }
}
