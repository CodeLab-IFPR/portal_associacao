<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Visualizar Função', only: ['index', 'show']),
            new Middleware('permission:Criar Função', only: ['create', 'store']),
            new Middleware('permission:Editar Função', only: ['edit', 'update']),
            new Middleware('permission:Deletar Função', only: ['destroy']),
        ];
    }
    public function index()
    {
        $roles = Role::all();
        $roles = Role::paginate(10);
        return view('funcoes.index', compact('roles'));
    }

    public function create()
    {
        $permissoes = Permission::orderBy('name', 'ASC')->get();
        return view('funcoes.create', compact('permissoes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name'
        ],[
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string.',
            'name.unique' => 'O nome já está em uso.'
        ]);

        $role = Role::create([
            'name' => $request->name
        ]);

        if(!empty($request->permissao)){
            foreach($request->permissao as $name){
                $role->givePermissionTo($name);
                
            }
        }

        return redirect()->route('funcoes.index')->with("status", "Função criada com sucesso.");
    }

    public function edit(Role $role): View
    {   
        $tem_permissoes = $role->permissions->pluck('name');
        $permissoes = Permission::orderBy('name', 'ASC')->get();
        return view("funcoes.edit", compact("role", "permissoes", "tem_permissoes"));
    }
    
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
        ],[
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string.',
            'name.unique' => 'O nome já está em uso.'
        ]);

        $role->update([
            'name' => $request->name
        ]);

        if(!empty($request->permissao)){
            $role->syncPermissions($request->permissao);
        } else {
            $role->syncPermissions([]);
        }

        return  redirect()->route('funcoes.index')->with("status", "Função atualizada com sucesso.");
    }

    public function destroy($id)
    {
        $role = Role::find($id);
        $role->delete();
        return  redirect()->route('funcoes.index')->with("status", "Função deletada com sucesso.");
    }
}
