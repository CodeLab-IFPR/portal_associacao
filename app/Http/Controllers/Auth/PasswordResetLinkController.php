<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Exibir a tela de solicitação de link de redefinição de senha.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Lidar com uma solicitação de link de redefinição de senha.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Enviar o link de redefinição de senha para este usuário. Uma vez que tentamos
        // enviar o link, examinaremos a resposta e veremos a mensagem que
        // precisamos mostrar ao usuário. Finalmente, enviaremos uma resposta adequada.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}
