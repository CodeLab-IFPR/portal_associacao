<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Verificação de E-mail</title>
  @vite('resources/css/libs.bundle.css')    
  @vite('resources/css/theme.bundle.css') 
  @vite('resources/css/styleLogin.css')    
</head>
<body>
<div class="container">
    <div class="login form">
        <h1 class="text-center display-4">Verificação de E-mail</h1>
        <hr>
        <div class="mb-4 text-sm">
            {{ __('Obrigado por se inscrever! Antes de começar, você poderia verificar seu endereço de e-mail clicando no link que acabamos de enviar para você?') }}<br><small>{{ __('Se você não recebeu o e-mail, ficaremos felizes em enviar outro.') }}</small>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ __('Um novo link de verificação foi enviado para o endereço de e-mail que você forneceu durante o registro.') }}
            </div>
        @endif

        <div class="mt-4 d-flex justify-content-between">
            <form method="POST" action="{{ route('verification.send') }}" class="mr-2">
            @csrf
            <button type="submit" class="btn btn-outline-primary">
                {{ __('Reenviar E-mail') }}
            </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-danger">
                {{ __('Sair') }}
            </button>
            </form>
        </div>
    </div>
</div>
  @vite('resources/js/vendor.bundle.js')
  @vite('resources/js/theme.bundle.js')
</body>
</html>
