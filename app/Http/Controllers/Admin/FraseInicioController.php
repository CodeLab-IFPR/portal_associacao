<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\FraseInicio;

class FraseInicioController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // Mesma permissão que mostra o item "Frases" no menu
            new Middleware('permission:Criar Frase'),
        ];
    }

    public function editar()
    {
        // Mesmo registro que atualizar() grava e a home exibe
        $fraseInicio = FraseInicio::first();
        $fraseSobre = FraseInicio::find(2);
        return view('admin.frase_inicio.editar', compact('fraseInicio', 'fraseSobre'));
    }

    public function atualizar(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'subtitulo' => 'required|string|max:255',
            'localizacao' => 'required|string|max:255',
            'frase_inicio' => 'required|string',
            'descricao' => 'required|string',
            'como_associar_se' => 'nullable|string',
        ]);

        $fraseInicio = FraseInicio::first();
        if ($fraseInicio) {
            $fraseInicio->update([
                'titulo' => $request->input('titulo'),
                'subtitulo' => $request->input('subtitulo'),
                'localizacao' => $request->input('localizacao'),
                'frase' => $request->input('frase_inicio'),
                'descricao' => $request->input('descricao'),
                'como_associar_se' => $request->input('como_associar_se'),
            ]);
        } else {
            FraseInicio::create([
                'titulo' => $request->input('titulo'),
                'subtitulo' => $request->input('subtitulo'),
                'localizacao' => $request->input('localizacao'),
                'frase' => $request->input('frase_inicio'),
                'descricao' => $request->input('descricao'),
                'como_associar_se' => $request->input('como_associar_se'),
            ]);
        }

        return redirect()->route('admin.frase_inicio.editar')->with('success', 'Frases atualizadas com sucesso!');
    }
}