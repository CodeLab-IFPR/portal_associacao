<?php

namespace App\Http\Controllers;

use App\Models\Parceiro;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Providers\ImageUploader;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\Middleware;

class ParceiroController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Visualizar Parceiro', only: ['index', 'show']),
            new Middleware('permission:Criar Parceiro', only: ['create', 'store']),
            new Middleware('permission:Editar Parceiro', only: ['edit', 'update']),
            new Middleware('permission:Deletar Parceiro', only: ['destroy']),
        ];
        
    }
    public function index(Request $request): View
    {
        $parceirosQuery = Parceiro::latest();
    
        if ($request->search) {
            $parceirosQuery->where(function ($query) use ($request) {
                $query->where('nome', 'like', "%{$request->search}%")
                      ->orWhere('email', 'like', "%{$request->search}%");
            });
        }
    
        $parceiros = $parceirosQuery->paginate(5);
    
        if (request()->ajax()) {
            return response()->json([
                'table' => view('parceiros.table', compact('parceiros'))->render()
            ]);
        }
    
        return view('parceiros.index', compact('parceiros'));
    }

    public function create(): View
    {
        return view('parceiros.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nome' => 'required|min:3|max:255',
            'email' => 'required|email',
            'link' => 'required|url',
            'alt' => 'required|min:5|max:250',
            'imagem' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ],[
            'nome.required' => 'O nome é obrigatório.',
            'nome.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'nome.max' => 'O nome deve ter no máximo 255 caracteres.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ser um endereço de email válido.',
            'email.min' => 'O email deve ter no mínimo 5 caracteres.',
            'email.max' => 'O email deve ter no máximo 250 caracteres.',
            'link.required' => 'O link é obrigatório.',
            'link.url' => 'O link deve ser uma URL válida.',
            'alt.required' => 'O alt é obrigatório.',
            'alt.min' => 'O alt deve ter no mínimo 5 caracteres.',
            'alt.max' => 'O alt deve ter no máximo 250 caracteres.',
            'imagem.required' => 'A imagem é obrigatória.',
            'imagem.image' => 'O arquivo deve ser uma imagem.',
            'imagem.mimes' => 'A imagem deve ser um dos seguintes formatos: jpeg, png, jpg, gif.',
            'imagem.max' => 'A imagem não pode ter mais que 2MB.',
        ]);
        $entrada = $request->all();

        if ($request->hasFile('imagem')) {
            $uploader = new ImageUploader();
            $uploader->setResolution(480);
            $uploader->setDestinationPath('parceiros/');
            $entrada['imagem'] = $uploader->upload($request->file('imagem'));
        }

        Parceiro::create($entrada);

        return redirect()->route("parceiros.index")
            ->with("success", "Parceiro criado com sucesso.");
    }

    public function show(Parceiro $parceiro): View
    {
        return view("parceiros.show", compact("parceiro"));
    }

    public function edit(Parceiro $parceiro): View
    {
        return view("parceiros.edit", compact("parceiro"));
    }

    public function update(Request $request, Parceiro $parceiro): RedirectResponse
    {

        try {

            $request->validate([
                'nome' => 'required|min:3|max:255',
                'email' => 'required|email',
                'link' => 'nullable|url',
                'alt' => 'nullable|min:5|max:250',
                'imagem' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ],[
                'nome.required' => 'O nome é obrigatório.',
                'nome.min' => 'O nome deve ter no mínimo 3 caracteres.',
                'nome.max' => 'O nome deve ter no máximo 255 caracteres.',
                'email.required' => 'O email é obrigatório.',
                'email.email' => 'O email deve ser um endereço de email válido.',
                'link.url' => 'O link deve ser uma URL válida.',
                'alt.min' => 'O alt deve ter no mínimo 5 caracteres.',
                'alt.max' => 'O alt deve ter no máximo 250 caracteres.',
                'imagem.image' => 'O arquivo deve ser uma imagem.',
                'imagem.mimes' => 'A imagem deve ser um dos seguintes formatos: jpeg, png, jpg, gif, svg.',
                'imagem.max' => 'A imagem não pode ter mais que 2MB.',
            ]);
            $entrada = $request->all();

            if ($request->hasFile('imagem')) {
                $uploader = new ImageUploader();
                $uploader->setCompression (30);
                $uploader->setDestinationPath('parceiros/');
                $entrada['imagem'] = $uploader->upload($request->file('imagem'), $parceiro->imagem);
            } else {
                unset($entrada['imagem']);
            }


            try {
                $parceiro->update($entrada);
            } catch (\Exception $e) {

                return redirect()->route('parceiros.index')
                                ->with('error', 'Erro ao atualizar parceiro.');
            }

            return redirect()->route('parceiros.index')
                            ->with('success', 'Parceiro atualizado.');

        } catch (\Illuminate\Validation\ValidationException $e) {

            return redirect()->route('parceiros.index')
                            ->withErrors($e->errors())
                            ->withInput();
        } catch (\Exception $e) {

            return redirect()->route('parceiros.index')
                            ->with('error', 'Erro inesperado ao atualizar o parceiro.');
            }
    }

    public function destroy($id)
    {
        try {
            $parceiro = Parceiro::findOrFail($id);
    
            if ($parceiro->imagem) {
                $imagePath = public_path('imagens/parceiros/' . $parceiro->imagem);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }
    
            $parceiro->delete();
    
            $parceiros = Parceiro::paginate(5);
    
            if (request()->ajax()) {
                return response()->json([
                    'table' => view('parceiros.table', compact('parceiros'))->render()
                ]);
            }
    
            return redirect()->route('parceiros.index')->with('success', 'Parceiro excluído com sucesso.');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao excluir o parceiro.'], 500);
        }
    }
}
