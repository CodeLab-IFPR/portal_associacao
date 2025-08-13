<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ServicoController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Visualizar Serviço', only: ['index', 'show']),
            new Middleware('permission:Criar Serviço', only: ['create', 'store']),
            new Middleware('permission:Editar Serviço', only: ['edit', 'update']),
            new Middleware('permission:Deletar Serviço', only: ['destroy']),
        ];
        
    }
    public function index()
    {
        $servicos = Servico::all();
        $servicos = Servico::paginate(10);
        return view('servicos.index', compact('servicos'));
    }

    public function create()
    {
        return view('servicos.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'descricao' => 'required|max:255',
        ],[
            'descricao.required' => 'O campo descrição é obrigatório.',
            'descricao.max' => 'O campo descrição deve ter no máximo 255 caracteres.',
        ]);

        Servico::create($request->all());

        return redirect()->route('servicos.index')
                         ->with('success', 'Serviço criado com sucesso.');
    }

    public function show(Servico $servico)
    {
        return view('servicos.show', compact('servico'));
    }

    public function edit(Servico $servico)
    {
        return view('servicos.edit', compact('servico'));
    }

    public function update(Request $request, Servico $servico)
    {
        $request->validate([
            'descricao' => 'required|max:255',
        ],[
            'descricao.required' => 'O campo descrição é obrigatório.',
            'descricao.max' => 'O campo descrição deve ter no máximo 255 caracteres.',
        ]);

        $servico->update($request->all());

        return redirect()->route('servicos.index')
                         ->with('success', 'Serviço atualizado com sucesso.');
    }

    public function destroy(Servico $servico)
    {
        try {
            $servico->delete();

            $servicos = Servico::paginate(10);

            if (request()->ajax()) {
                return response()->json([
                    'table' => view('servicos.table', compact('servicos'))->render()
                ]);
            }

            return redirect()->route('servicos.index')->with('success', 'Serviço deletado.');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao excluir o serviço.'], 500);
        }
    }
}