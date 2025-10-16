<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Verificação temporária para debug
        if (!auth()->check()) {
            abort(403, 'Usuário não autenticado.');
        }
        
        if (!auth()->user()->hasRole('Admin')) {
            // Vamos ver quais roles o usuário tem
            $userRoles = auth()->user()->roles->pluck('name')->toArray();
            abort(403, 'Acesso negado. Suas roles: ' . implode(', ', $userRoles) . '. Necessário: Admin');
        }
        
        $cargos = Cargo::orderBy('descricao')->paginate(10);
        return view('cargos.index', compact('cargos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cargos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'descricao' => 'required|string|max:255|unique:cargos,descricao'
        ], [
            'descricao.required' => 'A descrição do cargo é obrigatória.',
            'descricao.unique' => 'Já existe um cargo com esta descrição.',
            'descricao.max' => 'A descrição não pode ter mais de 255 caracteres.'
        ]);

        Cargo::create([
            'descricao' => $request->descricao
        ]);

        return redirect()->route('cargos.index')->with('success', 'Cargo criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cargo $cargo)
    {
        return view('cargos.show', compact('cargo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cargo $cargo)
    {
        return view('cargos.edit', compact('cargo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cargo $cargo)
    {
        $request->validate([
            'descricao' => 'required|string|max:255|unique:cargos,descricao,' . $cargo->id
        ], [
            'descricao.required' => 'A descrição do cargo é obrigatória.',
            'descricao.unique' => 'Já existe um cargo com esta descrição.',
            'descricao.max' => 'A descrição não pode ter mais de 255 caracteres.'
        ]);

        $cargo->update([
            'descricao' => $request->descricao
        ]);

        return redirect()->route('cargos.index')->with('success', 'Cargo atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cargo $cargo)
    {
        $cargo->delete();
        return redirect()->route('cargos.index')->with('success', 'Cargo excluído com sucesso!');
    }
}
