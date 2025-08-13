<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PermissaoController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Visualizar Permissão', only: ['index']),
            new Middleware('permission:Criar Permissão', only: ['create', 'store']),
            new Middleware('permission:Editar Permissão', only: ['edit', 'update']),
            new Middleware('permission:Deletar Permissão', only: ['destroy'])
        ];
    }
    public function index()
    {
        $permissoes = Permission::all();
        $permissoes = Permission::paginate(10);
        return view('permissoes.index', compact('permissoes'));
    }

    public function create()
    {
        return view('permissoes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|unique:permissions'
        ],[
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string.',
            'name.unique' => 'O nome já está em uso.'
        ]);

        $permissao = Permission::create([
            'name' => $request->input('name')
        ]);

        $permissao->save();
        return redirect()->route('permissoes.index')->with('success', 'Permissão cadastrada com sucesso.');
    }

    public function edit(Permission $permissao): View
    {   
        return view("permissoes.edit", compact("permissao"));
    }
    
    public function update(Request $request, Permission $permissao)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permissao->id,
        ],[
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string.',
            'name.unique' => 'O nome já está em uso.'
        ]);

        $permissao->update([
            'name' => $request->name
        ]);

        return redirect()->route('permissoes.index')->with("status", "Permissão atualizada com sucesso.");
    }

    public function destroy($id)
    {
        $permissao = Permission::find($id);
        $permissao->delete();
        return redirect()->route('permissoes.index')->with("status", "Permissão deletada com sucesso.");
    }
}
