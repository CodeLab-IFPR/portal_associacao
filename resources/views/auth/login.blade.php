<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Login - AMAER</title>
  <link rel="icon" type="image/png" href="{{ asset('img/amaer-ico.png') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  @vite('resources/css/styleLogin.css')
</head>
<body>
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <defs>
        <clipPath id="loginCurve" clipPathUnits="objectBoundingBox">
            <path d="M0,0 H0.92 C0.98,0.3 0.88,0.6 0.90,1 H0 Z." />
        </clipPath>
    </defs>
</svg>
  <div class="login-page">
    <aside class="login-institutional">
      <img src="{{ asset('img/imagem-tela-login-amaer.png') }}" alt="AMAER - Aeromodelismo e Automodelismo" class="login-institutional__image">
    </aside>

    <main class="login-auth">
      <div class="login-card">
        <div class="login-card__avatar" aria-hidden="true">
          <i class="bi bi-person"></i>
        </div>

        <h1 class="login-card__title">Entrar</h1>

        <form method="POST" action="{{ route('login') }}" class="login-form">
          @csrf

          @if (session('error'))
            <div class="login-form__error">{{ session('error') }}</div>
          @endif
          @if ($errors->has('email'))
            <div class="login-form__error">Email ou senha incorretos.</div>
          @endif

          <div class="login-form__field">
            <label for="email">E-mail</label>
            <div class="login-form__input-wrapper">
              <i class="bi bi-envelope login-form__icon" aria-hidden="true"></i>
              <input id="email" type="email" name="email" placeholder="Digite seu e-mail"
                     value="{{ old('email') }}" required autofocus autocomplete="username">
            </div>
          </div>

          <div class="login-form__field">
            <label for="password">Senha</label>
            <div class="login-form__input-wrapper">
              <i class="bi bi-lock login-form__icon" aria-hidden="true"></i>
              <input id="password" type="password" name="password" placeholder="Digite sua senha"
                     required autocomplete="current-password">
              <button type="button" class="login-form__toggle" id="togglePassword" aria-label="Mostrar senha">
                <i id="iconEyeOpen" class="bi bi-eye"></i>
                <i id="iconEyeClosed" class="bi bi-eye-slash" style="display:none;"></i>
              </button>
            </div>
          </div>

          <div class="login-form__row">
            <label class="login-form__remember">
              <input type="checkbox" name="remember" id="remember">
              <span>Lembrar-me</span>
            </label>
            <a href="{{ route('password.request') }}" class="login-form__forgot">Esqueci minha senha</a>
          </div>

          <button type="submit" class="login-form__btn login-form__btn--primary">Entrar</button>

          <div class="login-form__divider"><span>ou</span></div>

          <a href="{{ route('home') }}" class="login-form__btn login-form__btn--secondary">
            <i class="bi bi-globe" style="margin-right: 8px;"></i>
            Voltar ao Site
          </a>
        </form>
      </div>

      <p class="login-card__contact">
        Ainda não é associado?
        <a href="{{ route('contact') }}">Entre em contato conosco.</a>
      </p>

      <footer class="login-page__footer">
        &copy; {{ date('Y') }} AMAER - Todos os direitos reservados.
      </footer>
    </main>
  </div>

  <script>
    (function () {
      var toggle = document.getElementById('togglePassword');
      var input = document.getElementById('password');
      var iconOpen = document.getElementById('iconEyeOpen');
      var iconClosed = document.getElementById('iconEyeClosed');
      toggle.addEventListener('click', function () {
        var isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        iconOpen.style.display = isPassword ? 'none' : '';
        iconClosed.style.display = isPassword ? '' : 'none';
        toggle.setAttribute('aria-label', isPassword ? 'Ocultar senha' : 'Mostrar senha');
      });
    })();
  </script>
</body>
</html>
