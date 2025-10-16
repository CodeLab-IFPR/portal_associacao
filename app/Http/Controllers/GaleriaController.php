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

        $galerias = Galeria::latest()->paginate(12);

        return view('galeria.index', compact('galerias', 'anos'));
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
            'data_inicio_evento' => 'required|date',
            'data_fim_evento' => 'nullable|date|after_or_equal:data_inicio_evento',
            'imagem_principal' => 'required_if:tipo_midia,imagem|image|mimes:jpeg,png,jpg,gif|max:4096',
            'descricao' => 'nullable|string',
            'tipo_midia' => 'required|string|in:imagem,video',
            'link_youtube' => ['required_if:tipo_midia,video', 'nullable', 'url', 'regex:#^(https?\:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/)[a-zA-Z0-9_-]+#'],
            'arquivos' => 'required_if:tipo_midia,imagem|nullable|array',
            'arquivos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'titulo.required' => 'O campo título é obrigatório.',
            'data_inicio_evento.required' => 'A data de início do evento é obrigatória.',
            'data_fim_evento.after_or_equal' => 'A data de fim do evento não pode ser anterior à data de início.',
            'imagem_principal.required_if' => 'A imagem principal é obrigatória quando o tipo é imagem.',
            'imagem_principal.image' => 'O arquivo principal deve ser uma imagem.',
            'tipo_midia.required' => 'O tipo da mídia é obrigatório.',
            'link_youtube.required_if' => 'O link do YouTube é obrigatório quando o tipo é vídeo.',
            'link_youtube.regex' => 'O link deve ser uma URL válida do YouTube.',
            'arquivos.required_if' => 'A seleção de arquivos da galeria é obrigatória quando o tipo é imagem.',
            'arquivos.*.image' => 'Todos os arquivos selecionados para a galeria devem ser imagens.',
        ]);

        DB::beginTransaction();
        try {
            $mainImagePath = null;
            if ($request->hasFile('imagem_principal')) {
                $file = $request->file('imagem_principal');
                $mainImageName = Str::slug($request->titulo) . '-main-' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('img/fotos'), $mainImageName);
                $mainImagePath = 'img/fotos/' . $mainImageName;
            }

            $galeria = Galeria::create([
                'titulo' => $request->titulo,
                'descricao' => $request->descricao,
                'data_inicio_evento' => $request->data_inicio_evento,
                'data_fim_evento' => $request->data_fim_evento,
                'caminho' => $mainImagePath,
                'ano' => date('Y', strtotime($request->data_inicio_evento)),
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



                $galeria->update(['caminho' => $request->link_youtube]);

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
            'data_inicio_evento' => 'required|date',
            'data_fim_evento' => 'nullable|date|after_or_equal:data_inicio_evento',
            'descricao' => 'nullable|string',
            'tipo_midia' => 'required|string|in:imagem,video',
            'link_youtube' => ['required_if:tipo_midia,video', 'nullable', 'url', 'regex:#^(https?\:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/)[a-zA-Z0-9_-]+#'],
            'imagem_principal' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'arquivos' => 'nullable|array',
            'arquivos.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $galeria->fill([
                'titulo' => $request->titulo,
                'descricao' => $request->descricao,
                'data_inicio_evento' => $request->data_inicio_evento,
                'data_fim_evento' => $request->data_fim_evento,
                'ano' => date('Y', strtotime($request->data_inicio_evento)),
                'tipo' => $request->tipo_midia,
            ]);

            $originalType = $galeria->getOriginal('tipo');
            $newType = $request->tipo_midia;

            if ($originalType !== $newType) {
                if ($originalType === 'imagem') {
                    if ($galeria->getOriginal('caminho') && File::exists(public_path($galeria->getOriginal('caminho')))) {
                        File::delete(public_path($galeria->getOriginal('caminho')));
                    }
                }
                foreach ($galeria->midias as $midia) {
                    if ($midia->tipo === 'imagem' && File::exists(public_path($midia->caminho))) {
                        File::delete(public_path($midia->caminho));
                    }
                    $midia->delete();
                }
            }

            if ($newType === 'video') {
                $galeria->caminho = $request->link_youtube;
                $galeria->midias()->delete();
                GaleriaMidia::create([
                    'galeria_id' => $galeria->id,
                    'tipo' => 'video',
                    'caminho' => $request->link_youtube,
                ]);
            } else {
                if ($originalType === 'video') {
                    $galeria->caminho = null;
                }

                if ($request->hasFile('imagem_principal')) {
                    if ($galeria->caminho && File::exists(public_path($galeria->caminho))) {
                        File::delete(public_path($galeria->caminho));
                    }
                    $file = $request->file('imagem_principal');
                    $mainImageName = Str::slug($request->titulo) . '-main-' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('img/fotos'), $mainImageName);
                    $galeria->caminho = 'img/fotos/' . $mainImageName;
                }

                if ($request->hasFile('arquivos')) {
                    foreach ($request->file('arquivos') as $key => $mediaFile) {
                        $mediaFileName = Str::slug($request->titulo) . '-item-' . time() . '-' . ($key + 1) . '.' . $mediaFile->getClientOriginalExtension();
                        $mediaFile->move(public_path('img/fotos'), $mediaFileName);
                        GaleriaMidia::create([
                            'galeria_id' => $galeria->id,
                            'tipo' => 'imagem',
                            'caminho' => 'img/fotos/' . $mediaFileName,
                        ]);
                    }
                }
            }

            $galeria->save();
            DB::commit();

            return redirect()->route('galeria.indexAdmin')->with('success', 'Evento atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ocorreu um erro inesperado ao atualizar o evento. Tente novamente.');
        }
    }

    public function destroyMedia($id)
    {
        try {
            $midia = GaleriaMidia::findOrFail($id);

            if ($midia->tipo === 'imagem' && File::exists(public_path($midia->caminho))) {
                File::delete(public_path($midia->caminho));
            }

            $midia->delete();

            return response()->json(['success' => true, 'message' => 'Arquivo excluído com sucesso!']);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir o arquivo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $galeria = Galeria::findOrFail($id);

            if ($galeria->tipo === 'imagem' && $galeria->caminho && File::exists(public_path($galeria->caminho))) {
                File::delete(public_path($galeria->caminho));
            }

            foreach ($galeria->midias as $midia) {
                if ($midia->tipo === 'imagem' && File::exists(public_path($midia->caminho))) {
                    File::delete(public_path($midia->caminho));
                }
                $midia->delete();
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
