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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .password-requirements {
            list-style: none;
            padding-left: 0;
            margin: .5rem 0 0;
            font-size: .85rem;
        }
        .password-requirements li {
            display: flex;
            align-items: center;
            gap: .4rem;
            color: #dc3545;
            transition: color .15s ease-in-out;
        }
        .password-requirements li.is-valid {
            color: #198754;
        }
        .password-field {
            position: relative;
            display: flex;
            align-items: center;
        }
        .password-field .form-control {
            padding-right: 2.75rem;
        }
    </style>
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
                <div class="password-field">
                    <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" aria-describedby="password-requirements" />
                    <button type="button" class="login-form__toggle" data-toggle-password="password" aria-label="Mostrar senha" aria-pressed="false">
                        <i class="bi bi-eye" aria-hidden="true"></i>
                    </button>
                </div>
                <ul id="password-requirements" class="password-requirements" aria-live="polite">
                    <li data-rule="length"><i class="bi bi-x-circle" aria-hidden="true"></i> Mínimo de 8 caracteres</li>
                    <li data-rule="uppercase"><i class="bi bi-x-circle" aria-hidden="true"></i> Pelo menos 1 letra maiúscula</li>
                    <li data-rule="lowercase"><i class="bi bi-x-circle" aria-hidden="true"></i> Pelo menos 1 letra minúscula</li>
                    <li data-rule="symbol"><i class="bi bi-x-circle" aria-hidden="true"></i> Pelo menos 1 caractere especial</li>
                    <li data-rule="number"><i class="bi bi-x-circle" aria-hidden="true"></i> Pelo menos 1 número</li>
                </ul>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirmar Senha -->
            <div class="mb-3">
                <x-input-label for="password_confirmation" :value="__('Confirmar Senha')" />
                <div class="password-field">
                    <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" aria-describedby="password-match" />
                    <button type="button" class="login-form__toggle" data-toggle-password="password_confirmation" aria-label="Mostrar senha" aria-pressed="false">
                        <i class="bi bi-eye" aria-hidden="true"></i>
                    </button>
                </div>
                <ul id="password-match" class="password-requirements" aria-live="polite">
                    <li data-rule="match"><i class="bi bi-x-circle" aria-hidden="true"></i> As senhas devem ser iguais</li>
                </ul>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" id="reset-password-submit" class="btn btn-outline-primary">
                    {{ __('Redefinir Senha') }}
                </button>
            </div>
        </form>
    </div>
</div>
    @vite('resources/js/vendor.bundle.js')
    @vite('resources/js/theme.bundle.js')
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
