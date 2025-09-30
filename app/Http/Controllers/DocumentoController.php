<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class DocumentoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('admin') || $user->hasRole('Admin')) {
            // Admin pode ver todos os documentos
            $documentos = Documento::with(['user', 'aprovador'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            // Usuário comum só vê seus próprios documentos
            $documentos = $user->documentos()
                ->with('aprovador')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        return view('admin.documentos.index', compact('documentos'));
    }

    public function create()
    {
        return view('admin.documentos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'arquivo' => 'required|file|mimes:pdf|max:10240', // 10MB max
            'tipo_documento' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000'
        ], [
            'arquivo.required' => 'O arquivo é obrigatório.',
            'arquivo.mimes' => 'O arquivo deve ser um PDF.',
            'arquivo.max' => 'O arquivo não pode exceder 10MB.',
            'tipo_documento.required' => 'O tipo do documento é obrigatório.'
        ]);

        $arquivo = $request->file('arquivo');
        $nomeOriginal = $arquivo->getClientOriginalName();
        $nomeArquivo = time() . '_' . $nomeOriginal;
        
        // Salvar o arquivo no storage
        $caminhoArquivo = $arquivo->storeAs('documentos', $nomeArquivo, 'private');

        Documento::create([
            'user_id' => Auth::id(),
            'nome_arquivo' => $nomeArquivo,
            'nome_original' => $nomeOriginal,
            'caminho_arquivo' => $caminhoArquivo,
            'tipo_documento' => $request->tipo_documento,
            'descricao' => $request->descricao,
            'status' => 'pendente'
        ]);

        return redirect()->route('documentos.index')
            ->with('success', 'Documento enviado com sucesso! Aguarde a aprovação.');
    }

    public function show(Documento $documento)
    {
        $user = Auth::user();
        
        // Verificar se o usuário pode ver este documento
        if (!$user->hasRole('admin') && $documento->user_id !== $user->id) {
            abort(403, 'Você não tem permissão para ver este documento.');
        }

        return view('admin.documentos.show', compact('documento'));
    }

    public function download(Documento $documento)
    {
        $user = Auth::user();
        
        // Verificar se o usuário pode baixar este documento
        if (!$user->hasRole('admin') && $documento->user_id !== $user->id) {
            abort(403, 'Você não tem permissão para baixar este documento.');
        }

        if (!Storage::disk('private')->exists($documento->caminho_arquivo)) {
            abort(404, 'Arquivo não encontrado.');
        }

        return response()->download(
            storage_path('app/private/' . $documento->caminho_arquivo),
            $documento->nome_original
        );
    }

    public function edit(Documento $documento)
    {
        return view('admin.documentos.edit', compact('documento'));
    }

    public function update(Request $request, Documento $documento)
    {
        $request->validate([
            'arquivo' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
            'tipo_documento' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000'
        ], [
            'arquivo.mimes' => 'O arquivo deve ser um PDF.',
            'arquivo.max' => 'O arquivo não pode exceder 10MB.',
            'tipo_documento.required' => 'O tipo do documento é obrigatório.'
        ]);

        $documento->tipo_documento = $request->tipo_documento;
        $documento->descricao = $request->descricao;

        if ($request->hasFile('arquivo')) {
            if(Storage::exists($documento->caminho_arquivo)){
                Storage::delete($documento->caminho_arquivo);
            }
            $arquivo = $request->file('arquivo');
            $nomeOriginal = $arquivo->getClientOriginalName();
            $nomeArquivo = time() . '_' . $nomeOriginal;
        
            $caminhoArquivo = $arquivo->storeAs('documentos', $nomeArquivo, 'private');
            $documento->nome_arquivo = $nomeArquivo;
            $documento->nome_original = $nomeOriginal;
            $documento->caminho_arquivo = $caminhoArquivo;
        }

        $documento->update([
            'nome_arquivo' => $documento->nome_arquivo,
            'nome_original' => $documento->nome_original,
            'caminho_arquivo' => $documento->caminho_arquivo,
            'tipo_documento' => $request->tipo_documento,
            'descricao' => $request->descricao
        ]);

        return redirect()->route('documentos.index')
            ->with('success', 'Documento editado com sucesso!');
    }


    public function destroy(Documento $documento)
    {
        $user = Auth::user();
        
        // Verificar se o documento pode ser excluído
        if ($documento->status === 'aprovado') {
            return redirect()->back()
                ->with('error', 'Documentos aprovados não podem ser excluídos.');
        }

        // Verificar permissões
        if (!$user->hasRole('admin') && $documento->user_id !== $user->id) {
            abort(403, 'Você não tem permissão para excluir este documento.');
        }

        // Excluir o arquivo do storage
        if (Storage::disk('private')->exists($documento->caminho_arquivo)) {
            Storage::disk('private')->delete($documento->caminho_arquivo);
        }

        $documento->delete();

        return redirect()->route('documentos.index')
            ->with('success', 'Documento excluído com sucesso.');
    }

    public function aprovar(Documento $documento)
    {
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('Admin')) {
            abort(403, 'Apenas administradores podem aprovar documentos.');
        }

        $documento->update([
            'status' => 'aprovado',
            'aprovado_por' => Auth::id(),
            'aprovado_em' => now()
        ]);

        return redirect()->back()
            ->with('success', 'Documento aprovado com sucesso.');
    }

    public function rejeitar(Request $request, Documento $documento)
    {
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('Admin')) {
            abort(403, 'Apenas administradores podem rejeitar documentos.');
        }

        $request->validate([
            'observacoes' => 'required|string|max:1000'
        ], [
            'observacoes.required' => 'As observações são obrigatórias para rejeitar um documento.'
        ]);

        $documento->update([
            'status' => 'rejeitado',
            'aprovado_por' => Auth::id(),
            'aprovado_em' => now(),
            'observacoes' => $request->observacoes
        ]);

        return redirect()->back()
            ->with('success', 'Documento rejeitado com sucesso.');
    }

    /**
     * Mostrar documentos de um usuário específico
     */
    public function porUsuario(User $user)
    {
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('Admin')) {
            abort(403, 'Apenas administradores podem visualizar documentos de outros usuários.');
        }

        $documentos = $user->documentos()
            ->with('aprovador')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.documentos.index', compact('documentos', 'user'));
    }
}
