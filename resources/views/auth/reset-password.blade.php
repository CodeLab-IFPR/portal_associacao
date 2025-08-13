<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Redefinir Senha</title>
    @vite('resources/css/libs.bundle.css')    
    @vite('resources/css/theme.bundle.css') 
    @vite('resources/css/styleLogin.css')    
</head>
<body>
<div class="container d-flex justify-content-center align-items-center min-vh-90">
    <div class="card p-4" style="max-width: 100%; width: 100%;">
        <h1 class="text-center display-4">Redenifir a Senha</h1>
        <hr>
        <div class="mb-4 text-sm">
            {{ __('Por favor, insira seu endereço de e-mail, a nova senha e confirme a nova senha para redefinir sua senha.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Token de Redefinição de Senha -->
             <div class="mb-3">
                 <input type="hidden" name="token" value="{{ $request->route('token') }}">
             </div>

            <!-- Endereço de Email -->
            <div class="mb-3">
                <x-input-label for="email" :value="__('E-mail')" />
                <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Senha -->
            <div class="mb-3">
                <x-input-label for="password" :value="__('Senha')" />
                <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirmar Senha -->
            <div class="mb-3">
                <x-input-label for="password_confirmation" :value="__('Confirmar Senha')" />
                <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-outline-primary">
                    {{ __('Redefinir Senha') }}
                </button>
            </div>
        </form>
    </div>
</div>
    @vite('resources/js/vendor.bundle.js')
    @vite('resources/js/theme.bundle.js')
</body>
</html>
