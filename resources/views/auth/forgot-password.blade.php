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
    @vite('resources/css/forgot-password.css')
</head>
<body>

<div class="recover-shell">

    {{-- ── Coluna esquerda (imagem + brand) ── --}}
    <section class="recover-left" aria-hidden="true">
        <div class="recover-left-overlay"></div>
        <div class="recover-left-content"></div>
    </section>

    {{-- ── Coluna direita (formulário) ── --}}
    <section class="recover-right">
        <div class="recover-right-inner">

            <div class="recover-card">

                {{-- Ícone envelope --}}
                <div class="card-icon-wrap" aria-hidden="true">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#1a73e8" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="5" width="20" height="14" rx="2"/>
                        <path d="M2 7l10 7 10-7"/>
                    </svg>
                </div>

                <h1 class="card-title">Recuperar senha</h1>
                <div class="card-title-bar"></div>

                <p class="card-description">
                    Informe seu e-mail cadastrado que enviaremos um<br>link de redefinição de senha para você.
                </p>

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    {{-- Campo e-mail --}}
                    <div class="mb-3" style="margin-bottom:1rem;">
                        <label for="email" class="form-label">E-mail</label>
                        <div class="input-group @error('email') is-invalid-group @enderror">
                            <span class="input-group-text">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#6b7a90" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/>
                                    <path d="M22 6l-10 7L2 6"/>
                                </svg>
                            </span>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="Digite seu e-mail"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                            >
                        </div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Botão enviar --}}
                    <button type="submit" class="btn-primary-custom">
                        Enviar link de redefinição
                    </button>

                    {{-- Divisor --}}
                    <div class="divider-or">ou</div>

                    {{-- Voltar ao site --}}
                    <a href="{{ route('home') }}" class="btn-outline-custom">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                        </svg>
                        Voltar ao site
                    </a>

                    {{-- Mensagem de erro geral (abaixo do botao Voltar ao site) --}}
                    @if ($errors->any())
                        <div class="alert-error-custom" role="alert" aria-live="polite">
                            <span class="alert-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M12 8v4"/>
                                    <path d="M12 16h.01"/>
                                </svg>
                            </span>
                            <div>
                                <span class="alert-title">Não foi possível enviar o link</span>
                                <p class="alert-body">{{ $errors->first() }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Mensagem de sucesso --}}
                    @if (session('status'))
                        <div class="alert-success-custom" role="status" aria-live="polite">
                            <span class="alert-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M9 12l2 2 4-4"/>
                                </svg>
                            </span>
                            <div>
                                <p class="alert-title">Link enviado com sucesso!</p>
                                <p class="alert-body">Enviaremos um link de redefinição para seu e-mail em alguns minutos.</p>
                            </div>
                        </div>
                    @endif

                </form>
            </div>

            {{-- Rodapé --}}
            <div class="recover-footer">
                <p>
                    Lembrou sua senha?
                    <a href="{{ route('login') }}">Faça login</a>
                </p>
                <p>© 2024 AMAER - Todos os direitos reservados.</p>
            </div>

        </div>
    </section>

</div>

    @vite('resources/js/vendor.bundle.js')
    @vite('resources/js/theme.bundle.js')
</body>
</html>