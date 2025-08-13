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
            'frase_inicio' => 'required|string',
            'frase_sobre' => 'required|string',
        ]);

        $fraseInicio = FraseInicio::find(1);
        if ($fraseInicio) {
            $fraseInicio->update(['frase' => $request->input('frase_inicio')]);
        } else {
            FraseInicio::create(['frase' => $request->input('frase_inicio')]);
        }

        $fraseSobre = FraseInicio::find(2);
        if ($fraseSobre) {
            $fraseSobre->update(['frase' => $request->input('frase_sobre')]);
        } else {
            FraseInicio::create(['frase' => $request->input('frase_sobre')]);
        }

        return redirect()->route('admin.frase_inicio.editar')->with('success', 'Frases atualizadas com sucesso!');
    }
}