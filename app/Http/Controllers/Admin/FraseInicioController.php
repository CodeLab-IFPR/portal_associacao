<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FraseInicio;

class FraseInicioController extends Controller
{
    public function editar()
    {
        $fraseInicio = FraseInicio::find(1);
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