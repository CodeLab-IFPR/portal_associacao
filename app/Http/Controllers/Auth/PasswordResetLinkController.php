<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

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
            'email' => ['required', 'email', Rule::exists(User::class, 'email')],
        ], [
            'email.exists' => 'O e-mail informado não possui cadastro.',
        ]);

        // Enviar o link de redefinição de senha para este usuário. Uma vez que tentamos
        // enviar o link, examinaremos a resposta e veremos a mensagem que
        // precisamos mostrar ao usuário. Finalmente, enviaremos uma resposta adequada.
        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );
        } catch (Throwable $exception) {
            report($exception);

            return back()->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Não foi possível enviar o link. Verifique as configurações de e-mail.',
                ]);
        }

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        $message = match ($status) {
            Password::INVALID_USER => 'O e-mail informado não possui cadastro.',
            Password::RESET_THROTTLED => 'Aguarde alguns minutos antes de solicitar novamente.',
            default => 'Não foi possível enviar o link. Tente novamente.',
        };

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => $message]);
    }
}
