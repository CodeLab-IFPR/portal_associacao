<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Formulário de Login e Registro</title>
  @vite('resources/css/styleLogin.css')    
</head>
<body>
  <div class="container">
    <input type="checkbox" id="check">
    <div class="login form">
      <header>Login</header>
      <form method="POST" action="{{ route('login') }}">
        @csrf
        <!-- Mensagem de Erro -->
        @if (session('error'))
            <div class="error-message">
                {{ session('error') }}
            </div>
        @endif 
        @if ($errors->has('email'))
            <div class="error-message">
                Email ou senha incorretos.
            </div>
        @endif

        <label for="email" class="email-label">E-mail</label>
        <input type="email" name="email" placeholder="Digite seu e-mail" value="{{ old('email') }}" required autofocus autocomplete="username">
        
        <label for="password" class="password-label">Senha</label> 
        <input type="password" name="password" placeholder="Digite sua senha" required autocomplete="current-password">
        
        <div class="remember-forgot">
            <div class="remember">
              <input type="checkbox" name="remember" id="remember">
              <label for="remember">Lembrar-me</label>
            </div>

            <div class="forgot-password">
              <a href="{{ route('password.request') }}">Esqueceu a senha?</a>
            </div>
        </div>
        
        <input type="submit" class="button" value="Entrar">
      </form>
        </a>
      </div>
    </div>
  </div>
</body>
</html>