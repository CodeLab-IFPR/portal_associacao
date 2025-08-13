<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleLoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                return redirect()->route('login')->with('error', 'Usuário não autorizado, falar com o administrador.');
            }

            // Gerar uma senha Google se não existir
            if (!$user->passwordGoogle) {
                $user->passwordGoogle = bcrypt(uniqid());
                $user->save();
            }

            // Faça o login do usuário
            Auth::login($user);

            return redirect()->route('admin'); // Redirecione para a página inicial
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Falha no login com Google.');
        }
    }
}
