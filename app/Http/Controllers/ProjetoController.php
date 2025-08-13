<?php
// app/Http/Controllers/ProjetoController.php
namespace App\Http\Controllers;

use App\Models\Projeto;
use App\Models\Tag;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\Middleware;

class ProjetoController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Visualizar Projeto', only: ['index', 'show']),
            new Middleware('permission:Criar Projeto', only: ['create', 'store']),
            new Middleware('permission:Editar Projeto', only: ['edit', 'update']),
            new Middleware('permission:Deletar Projeto', only: ['destroy']),
        ];
    }
    public function index(): View
    {
        $projetos = Projeto::paginate(10);
        return view('projetos.index', compact('projetos'));
    }

    public function create(): View
    {
        $tags = Tag::all();
        return view('projetos.create', compact('tags'));
    }

    public function indexPublic(): View
    {
        $projetos = Projeto::latest()->paginate(6);
        return view('projetos.cards', compact('projetos'));
    }

    public function home(): View
    {
        $projetos = Projeto::latest()->take(6)->get();
        return view('home', compact('projetos'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nome' => 'required|min:3|max:255',
            'descricao' => 'required|min:5',
            'imagem' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
        ],[
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.min' => 'O campo nome deve ter no mínimo 3 caracteres.',
            'nome.max' => 'O campo nome deve ter no máximo 255 caracteres.',
            'descricao.required' => 'O campo descrição é obrigatório.',
            'descricao.min' => 'O campo descrição deve ter no mínimo 5 caracteres.',
        ]);

        $status = $request->has('status') ? 'concluido' : 'em aberto';

        $projeto = Projeto::create([
            'nome' => $request->input('nome'),
            'descricao' => $request->input('descricao'),
            'status' => $status,
        ]);

        if ($request->hasFile('imagem')) {
            $file = $request->file('imagem');
            $fileName = date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('imagens/projetos'), $fileName);
            $projeto->imagem = 'imagens/projetos/' . $fileName;
            $projeto->save();
        }

        $projeto->tags()->sync($request->input('tags', [])); 
        
        return redirect()->route('projetos.index')
            ->with('success', 'Projeto criado com sucesso.');
    }
    
    public function show(Projeto $projeto): View
    {
        return view('projetos.show', compact('projeto'));
    }
    
    public function edit(Projeto $projeto): View
    {
        $tags = Tag::all();
        return view('projetos.edit', compact('projeto', 'tags'));
    }

    public function update(Request $request, Projeto $projeto): RedirectResponse
    {
        $request->validate([
            'nome' => 'required|min:3|max:255',
            'descricao' => 'required|min:5',
            'imagem' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
        ],[
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.min' => 'O campo nome deve ter no mínimo 3 caracteres.',
            'nome.max' => 'O campo nome deve ter no máximo 255 caracteres.',
            'descricao.required' => 'O campo descrição é obrigatório.',
            'descricao.min' => 'O campo descrição deve ter no mínimo 5 caracteres.',
        ]);

        $status = $request->has('status') ? 'concluido' : 'em aberto';

        $projeto->update([
            'nome' => $request->input('nome'),
            'descricao' => $request->input('descricao'),
            'status' => $status,
        ]);

        if ($request->hasFile('imagem')) {
            $file = $request->file('imagem');
            $fileName = date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('imagens/projetos'), $fileName);
            $projeto->imagem = 'imagens/projetos/' . $fileName;
            $projeto->save();
        }

        $projeto->tags()->sync($request->input('tags', [])); // Sync tags to the pivot table

        return redirect()->route('projetos.index')
            ->with('success', 'Projeto atualizado com sucesso.');
    }

    public function destroy(Projeto $projeto)
    {
        try {
            $projeto->delete();

            $projetos = Projeto::paginate(10);

            if (request()->ajax()) {
                return response()->json([
                    'table' => view('projetos.table', compact('projetos'))->render()
                ]);
            }

            return redirect()->route('projetos.index')->with('success', 'Projeto deletado.');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao excluir o projeto.'], 500);
        }
    }
}
