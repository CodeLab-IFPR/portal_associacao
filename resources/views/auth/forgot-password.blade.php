<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Esqueceu a Senha?</title>
    @vite('resources/css/libs.bundle.css')    
    @vite('resources/css/theme.bundle.css') 
    @vite('resources/css/styleLogin.css')    
</head>
<body>
<div class="container d-flex justify-content-center align-items-center min-vh-90">
    <div class="card p-4" style="max-width: 100%; width: 100%;">
        <h1 class="text-center display-4">Esqueceu a Senha?</h1>
        <hr>
        <div class="mb-4 text-sm">
            {{ __('Esqueceu sua senha? Não tem problema. Apenas nos informe seu endereço de e-mail e enviaremos um link de redefinição de senha que permitirá que você escolha uma nova.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-3">
                <x-input-label for="email" :value="__('E-mail')" />
                <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-outline-primary">
                    {{ __('Enviar Link de Redefinição') }}
                </button>
            </div>
        </form>
    </div>
</div>
    @vite('resources/js/vendor.bundle.js')
    @vite('resources/js/theme.bundle.js')
</body>
</html>
