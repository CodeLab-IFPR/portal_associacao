<?php

namespace App\Http\Controllers;

use App\Models\Ata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AtaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            'verified',
            new Middleware('role:admin|Admin', except: ['index', 'show']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $atas = Ata::latest()->paginate(10);
        return view('admin.atas.index', compact('atas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.atas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'arquivo' => 'required|file|mimes:pdf|max:10240', // 10MB max
        ], [
            'titulo.required' => 'O título é obrigatório.',
            'descricao.required' => 'A descrição é obrigatória.',
            'arquivo.required' => 'O arquivo PDF é obrigatório.',
            'arquivo.mimes' => 'O arquivo deve ser um PDF.',
            'arquivo.max' => 'O arquivo não pode ter mais de 10MB.',
        ]);

        $ata = new Ata();
        $ata->titulo = $request->titulo;
        $ata->descricao = $request->descricao;

        // Handle file upload
        if ($request->hasFile('arquivo')) {
            $file = $request->file('arquivo');
            $originalName = $file->getClientOriginalName();
            $fileName = time() . '_' . Str::random(10) . '.pdf';
            
            // Store file in storage/app/public/atas
            $file->storeAs('public/atas', $fileName);
            
            $ata->arquivo = $fileName;
            $ata->arquivo_original = $originalName;
        }

        $ata->save();

        return redirect()->route('admin.atas.index')
            ->with('success', 'ATA cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ata $ata)
    {
        return view('admin.atas.show', compact('ata'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ata $ata)
    {
        return view('admin.atas.edit', compact('ata'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ata $ata)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'arquivo' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
        ], [
            'titulo.required' => 'O título é obrigatório.',
            'descricao.required' => 'A descrição é obrigatória.',
            'arquivo.mimes' => 'O arquivo deve ser um PDF.',
            'arquivo.max' => 'O arquivo não pode ter mais de 10MB.',
        ]);

        $ata->titulo = $request->titulo;
        $ata->descricao = $request->descricao;

        // Handle file upload if new file is provided
        if ($request->hasFile('arquivo')) {
            // Delete old file if exists
            if ($ata->arquivo && Storage::exists('public/atas/' . $ata->arquivo)) {
                Storage::delete('public/atas/' . $ata->arquivo);
            }

            $file = $request->file('arquivo');
            $originalName = $file->getClientOriginalName();
            $fileName = time() . '_' . Str::random(10) . '.pdf';
            
            // Store new file
            $file->storeAs('public/atas', $fileName);
            
            $ata->arquivo = $fileName;
            $ata->arquivo_original = $originalName;
        }

        $ata->save();

        return redirect()->route('admin.atas.index')
            ->with('success', 'ATA atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ata $ata)
    {
        // Delete file if exists
        if ($ata->arquivo && Storage::exists('public/atas/' . $ata->arquivo)) {
            Storage::delete('public/atas/' . $ata->arquivo);
        }

        $ata->delete();

        return redirect()->route('admin.atas.index')
            ->with('success', 'ATA excluída com sucesso!');
    }

    /**
     * Download the ATA file
     */
    public function download(Ata $ata)
    {
        if (!$ata->arquivo || !Storage::exists('public/atas/' . $ata->arquivo)) {
            abort(404, 'Arquivo não encontrado.');
        }

        return Storage::download('public/atas/' . $ata->arquivo, $ata->arquivo_original);
    }
}
