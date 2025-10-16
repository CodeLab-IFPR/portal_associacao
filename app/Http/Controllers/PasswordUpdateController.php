<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PasswordUpdateController extends Controller
{
    /**
     * Exibe a página de alteração de senha.
     */
    public function edit(Request $request): View
    {
        return view('profile.password', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Atualiza a senha do usuário.
     */
    public function update(Request $request): RedirectResponse
    {
        // Log para debug
        Log::info('Password update attempt', [
            'user_id' => $request->user()->id,
            'has_current_password' => !empty($request->current_password),
            'has_new_password' => !empty($request->password),
            'has_confirmation' => !empty($request->password_confirmation),
        ]);

        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ]);

        // Verifica se a senha atual está correta
        if (!Hash::check($request->current_password, $request->user()->password)) {
            Log::warning('Current password incorrect for user', ['user_id' => $request->user()->id]);
            return back()->withErrors([
                'current_password' => 'A senha atual está incorreta.'
            ], 'updatePassword');
        }

        // Atualiza a senha
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        Log::info('Password updated successfully', ['user_id' => $request->user()->id]);

        return back()->with('status', 'password-updated');
    }
}
