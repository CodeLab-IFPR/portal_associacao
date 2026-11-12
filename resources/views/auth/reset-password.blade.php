<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Redefinir Senha - AMAER</title>
  <link rel="icon" type="image/png" href="{{ asset('img/amaer-ico.png') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  @vite('resources/css/styleLogin.css')
</head>
<body>
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <defs>
        <clipPath id="loginCurve" clipPathUnits="objectBoundingBox">
            <path d="M0,0 H0.92 C0.98,0.3 0.88,0.6 0.90,1 H0 Z" />
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
          <i class="bi bi-shield-lock"></i>
        </div>

        <h1 class="login-card__title">Redefinir senha</h1>

        <p class="login-card__description">
          Confirme seu e-mail e crie uma nova senha que atenda aos requisitos abaixo.
        </p>

        <form method="POST" action="{{ route('password.store') }}" class="login-form">
          @csrf

          <!-- Token de Redefinição de Senha -->
          <input type="hidden" name="token" value="{{ $request->route('token') }}">

          @if (session('status'))
            <div class="login-form__success" role="status">{{ session('status') }}</div>
          @endif

          <!-- Endereço de E-mail -->
          <div class="login-form__field">
            <label for="email">E-mail</label>
            <div class="login-form__input-wrapper @error('email') login-form__input-wrapper--invalid @enderror">
              <i class="bi bi-envelope login-form__icon" aria-hidden="true"></i>
              <input id="email" type="email" name="email" placeholder="Digite seu e-mail"
                     value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                     @error('email') aria-invalid="true" @enderror>
            </div>
            <x-input-error :messages="$errors->get('email')" class="login-form__field-error" />
          </div>

          <!-- Nova Senha -->
          <div class="login-form__field">
            <label for="password">Nova senha</label>
            <div class="login-form__input-wrapper @error('password') login-form__input-wrapper--invalid @enderror">
              <i class="bi bi-lock login-form__icon" aria-hidden="true"></i>
              <input id="password" type="password" name="password" placeholder="Digite sua nova senha"
                     required autocomplete="new-password" aria-describedby="password-requirements"
                     @error('password') aria-invalid="true" @enderror>
              <button type="button" class="login-form__toggle" data-toggle-password="password" aria-label="Mostrar senha" aria-pressed="false">
                <i class="bi bi-eye" aria-hidden="true"></i>
              </button>
            </div>
            <ul id="password-requirements" class="login-form__requirements" aria-live="polite">
              <li data-rule="length"><i class="bi bi-x-circle" aria-hidden="true"></i> Mínimo de 8 caracteres</li>
              <li data-rule="uppercase"><i class="bi bi-x-circle" aria-hidden="true"></i> Pelo menos 1 letra maiúscula</li>
              <li data-rule="lowercase"><i class="bi bi-x-circle" aria-hidden="true"></i> Pelo menos 1 letra minúscula</li>
              <li data-rule="symbol"><i class="bi bi-x-circle" aria-hidden="true"></i> Pelo menos 1 caractere especial</li>
              <li data-rule="number"><i class="bi bi-x-circle" aria-hidden="true"></i> Pelo menos 1 número</li>
            </ul>
            <x-input-error :messages="$errors->get('password')" class="login-form__field-error" />
          </div>

          <!-- Confirmar Senha -->
          <div class="login-form__field">
            <label for="password_confirmation">Confirmar nova senha</label>
            <div class="login-form__input-wrapper @error('password_confirmation') login-form__input-wrapper--invalid @enderror">
              <i class="bi bi-lock login-form__icon" aria-hidden="true"></i>
              <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Repita a nova senha"
                     required autocomplete="new-password" aria-describedby="password-match"
                     @error('password_confirmation') aria-invalid="true" @enderror>
              <button type="button" class="login-form__toggle" data-toggle-password="password_confirmation" aria-label="Mostrar senha" aria-pressed="false">
                <i class="bi bi-eye" aria-hidden="true"></i>
              </button>
            </div>
            <ul id="password-match" class="login-form__requirements" aria-live="polite">
              <li data-rule="match"><i class="bi bi-x-circle" aria-hidden="true"></i> As senhas devem ser iguais</li>
            </ul>
            <x-input-error :messages="$errors->get('password_confirmation')" class="login-form__field-error" />
          </div>

          <button type="submit" id="reset-password-submit" class="login-form__btn login-form__btn--primary">
            Redefinir senha
          </button>

          <div class="login-form__divider"><span>ou</span></div>

          <a href="{{ route('home') }}" class="login-form__btn login-form__btn--secondary">
            <i class="bi bi-globe" style="margin-right: 8px;"></i>
            Voltar ao Site
          </a>
        </form>
      </div>

      <p class="login-card__contact">
        Lembrou sua senha?
        <a href="{{ route('login') }}">Faça login.</a>
      </p>

      <footer class="login-page__footer">
        &copy; {{ date('Y') }} AMAER - Todos os direitos reservados.
      </footer>
    </main>
  </div>

    <script>
        (function () {
            const password = document.getElementById('password');
            const confirmation = document.getElementById('password_confirmation');
            const submit = document.getElementById('reset-password-submit');

            // Mesmas regras (e regex) usadas por Rules\Password no NewPasswordController,
            // para que o front nunca aprove uma senha que o backend recusaria.
            const rules = {
                length: (value) => Array.from(value).length >= 8,
                uppercase: (value) => /\p{Lu}/u.test(value),
                lowercase: (value) => /\p{Ll}/u.test(value),
                symbol: (value) => /\p{Z}|\p{S}|\p{P}/u.test(value),
                number: (value) => /\p{N}/u.test(value),
                match: (value, confirmValue) => confirmValue.length > 0 && value === confirmValue,
            };

            function setState(item, valid) {
                const icon = item.querySelector('i');
                item.classList.toggle('is-valid', valid);
                icon.classList.toggle('bi-check-circle', valid);
                icon.classList.toggle('bi-x-circle', !valid);
            }

            function validate() {
                let allValid = true;
                document.querySelectorAll('[data-rule]').forEach((item) => {
                    const valid = rules[item.dataset.rule](password.value, confirmation.value);
                    setState(item, valid);
                    allValid = allValid && valid;
                });
                submit.disabled = !allValid;
            }

            document.querySelectorAll('[data-toggle-password]').forEach((toggle) => {
                const input = document.getElementById(toggle.dataset.togglePassword);
                const icon = toggle.querySelector('i');
                toggle.addEventListener('click', () => {
                    const show = input.type === 'password';
                    input.type = show ? 'text' : 'password';
                    icon.classList.toggle('bi-eye', !show);
                    icon.classList.toggle('bi-eye-slash', show);
                    toggle.setAttribute('aria-label', show ? 'Ocultar senha' : 'Mostrar senha');
                    toggle.setAttribute('aria-pressed', String(show));
                });
            });

            password.addEventListener('input', validate);
            confirmation.addEventListener('input', validate);
            validate();
        })();
    </script>
</body>
</html>
