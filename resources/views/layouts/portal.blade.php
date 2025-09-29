<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Amaer - Associação Maringaense de Aeromodelismo e Automodelismo Radio Controlado.">
    <meta name="author" content="Cadu e João">
    <meta name="keywords" content="">

    <link rel="apple-touch-icon" sizes="180x180" src="{{ asset('img/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/amaer-ico.png')  }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/amaer-ico.png')  }}">
    <link rel="mask-icon" src="{{ asset('img/favicon/safari-pinned-tab.svg') }}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const token = urlParams.get('token');

        if (token) {
            const campoToken = document.getElementById('token');
            if (campoToken) {
                campoToken.value = token;
            }

            const formulario = document.getElementById('validar-certificado-form');
            if (formulario) {
                // Enviar o formulário via JavaScript
                fetch('{{ route('certificados.validar.post') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: new URLSearchParams({
                        'token': token,
                        '_token': '{{ csrf_token() }}'
                    })
                })
                .then(response => response.json())
                .then(data => handleResponse(data))
                .catch(error => {
                    console.error('Erro:', error);
                    document.getElementById('error-message').innerText = 'Ocorreu um erro ao validar o certificado.';
                    document.getElementById('error-message').style.display = 'block';
                    document.getElementById('certificado-detalhes').style.display = 'none';
                });
            }
        }
    });
</script>

    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }

        /* Footer Styles */
        .hover-text-primary:hover {
            color: #007bff !important;
        }

        .hover-text-white:hover {
            color: #ffffff !important;
        }

        .border-white-10 {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        .text-white-50 {
            color: rgba(255, 255, 255, 0.5) !important;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        footer .footer-nav li a:hover {
            padding-left: 0.5rem;
            transition: all 0.3s ease;
        }

        footer i.ri-map-pin-line,
        footer i.ri-mail-line,
        footer i.ri-phone-line {
            color: #007bff;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            footer .d-flex.gap-3 {
                justify-content: center;
            }

            footer h6 {
                text-align: center;
                margin-bottom: 1rem !important;
            }

            footer .footer-nav {
                text-align: center;
            }
        }
    </style>

    @vite('resources/css/libs.bundle.css')
    @vite('resources/css/theme.bundle.css')
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <noscript>
        <style>
          .simplebar-content-wrapper {
            overflow: auto;
          }
        </style>
    </noscript>
    <title>@yield('title')</title>

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white ">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="ri-menu-line"></i>
        </button>
        <div class="collapse navbar-collapse justify-content-evenly" id="navbarSupportedContent">
            <ul class="navbar-nav" style="gap: 0.3rem;">

                <li class="nav-item">
                    <a class="nav-link me-lg-0 ms-lg-0" href="{{ route('noticias.cards') }}" style="font-size: 1.2rem;">
                        <i class="ri-newspaper-line me-2"></i>
                        <span>Notícias</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link me-lg-0 ms-lg-0" href="{{ route('about') }}" style="font-size: 1.2rem;">
                        <i class="ri-information-line me-2"></i>
                        <span>Sobre Nós</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-lg-0 ms-lg-0" href="{{ route('contact') }}" style="font-size: 1.2rem;">
                        <i class="ri-contacts-line me-2"></i>
                        <span>Contato</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-lg-0 ms-lg-0" href="{{ route('galeria.indexPublic') }}" style="font-size: 1.2rem;">
                        <i class="ri-gallery-line me-2"></i>
                        <span>Galeria</span>
                    </a>
                </li>
                      <li class="nav-item">
                    <a class="nav-link me-lg-0 ms-lg-0" href="{{ route('member.register.form') }}" style="font-size: 1.2rem;">
                        <i class="ri-user-add-line me-2"></i>
                        <span>Seja Membro</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-lg-0 ms-lg-0" href="{{ route('admin') }}" style="font-size: 1.2rem;">
                        <i class="ri-settings-3-line me-2"></i>
                        <span>Área do Associado</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<main class="mt-0 ">
    @yield('content')
</main>
<footer class="bg-dark py-8">
    <div class="container">
        <!-- Seção principal do footer -->
        <div class="row g-4 mb-6">
            <!-- Logo e descrição -->
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center mb-4">
                    <span class="f-w-7 d-block text-success me-3">
                        <img class="img-fluid d-table mx-auto bg-white rounded-2 p-1" src="{{ asset('img/amaer-ico.png') }}" alt="AMAER Logo">
                    </span>
                    <div>
                        <h5 class="text-white fw-bold mb-1">AMAER</h5>
                        <small class="text-white-50">Associação de Aeromodelismo e Automodelismo</small>
                    </div>
                </div>
                <p class="text-white-50 mb-4">Promovendo o aeromodelismo e automodelismo em Paranavaí - PR com segurança, educação e diversão para toda a família.</p>
                <div class="d-flex gap-3">
                    <a href="#" class="text-white-50 hover-text-primary transition-all">
                        <i class="ri-facebook-fill fs-5"></i>
                    </a>
                    <a href="#" class="text-white-50 hover-text-primary transition-all">
                        <i class="ri-instagram-line fs-5"></i>
                    </a>
                    <a href="#" class="text-white-50 hover-text-primary transition-all">
                        <i class="ri-youtube-line fs-5"></i>
                    </a>
                    <a href="#" class="text-white-50 hover-text-primary transition-all">
                        <i class="ri-mail-line fs-5"></i>
                    </a>
                </div>
            </div>

            <!-- Links rápidos -->
            <div class="col-lg-2 col-md-3 col-sm-6">
                <h6 class="text-white fw-bold mb-4">Portal</h6>
                <ul class="list-unstyled footer-nav">
                    <li class="mb-2"><a href="{{ route('about') }}" class="text-white-50 text-decoration-none hover-text-white transition-all">Sobre Nós</a></li>
                    <li class="mb-2"><a href="{{ route('member.register.form') }}" class="text-white-50 text-decoration-none hover-text-white transition-all">Seja Membro</a></li>
                    <li class="mb-2"><a href="{{ route('noticias.cards') }}" class="text-white-50 text-decoration-none hover-text-white transition-all">Notícias</a></li>
                    <li class="mb-2"><a href="{{ route('galeria.indexPublic') }}" class="text-white-50 text-decoration-none hover-text-white transition-all">Galeria</a></li>
                </ul>
            </div>

            <!-- Navegação -->
            <div class="col-lg-2 col-md-3 col-sm-6">
                <h6 class="text-white fw-bold mb-4">Navegação</h6>
                <ul class="list-unstyled footer-nav">
                    <li class="mb-2"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none hover-text-white transition-all">Home</a></li>
                    <li class="mb-2"><a href="{{ route('contact') }}" class="text-white-50 text-decoration-none hover-text-white transition-all">Contato</a></li>
                    <li class="mb-2"><a href="{{ route('admin') }}" class="text-white-50 text-decoration-none hover-text-white transition-all">Área do Associado</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-text-white transition-all">Projetos</a></li>
                </ul>
            </div>

            <!-- Contato e informações -->
            <div class="col-lg-4 col-md-6">
                <h6 class="text-white fw-bold mb-4">Contato & Localização</h6>
                <div class="mb-3">
                    <div class="d-flex align-items-start mb-2">
                        <i class="ri-map-pin-line text-primary me-2 mt-1"></i>
                        <span class="text-white-50 small">Maringá - PR<br>Brasil</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="ri-mail-line text-primary me-2"></i>
                        <a href="mailto:contato@amaer.com.br" class="text-white-50 text-decoration-none hover-text-white transition-all small">contato@amaer.com.br</a>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="ri-phone-line text-primary me-2"></i>
                        <span class="text-white-50 small">(44) 9999-9999</span>
                    </div>
                </div>

                <h6 class="text-white fw-bold mb-3 mt-4">Horário de Funcionamento</h6>
                <div class="text-white-50 small">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Sábados:</span>
                        <span>08:00 - 17:00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Domingos:</span>
                        <span>08:00 - 16:00</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Segunda - Sexta:</span>
                        <span>Consultar</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de termos legais -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="border-top border-white-10 pt-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h6 class="text-white fw-bold mb-3">Links Legais</h6>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="#" class="text-white-50 text-decoration-none hover-text-white transition-all small">Política de Privacidade</a>
                                <a href="#" class="text-white-50 text-decoration-none hover-text-white transition-all small">Termos & Condições</a>
                                <a href="#" class="text-white-50 text-decoration-none hover-text-white transition-all small">LGPD</a>
                                <a href="https://ifpr.edu.br/paranavai/" target="_blank" class="text-white-50 text-decoration-none hover-text-white transition-all small">IFPR Paranavaí</a>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <div class="text-white-50 small">
                                <div><a href="https://codelabifpr.com.br" target="_blank" class="text-white-50 text-decoration-none hover-text-white transition-all ">Desenvolvido com ❤️ pelo CodeLab IFPR</a></div>
                                <div class="mt-1">Todos os direitos reservados © AMAER 2024</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</footer>
<div class="modal fade modal-video" id="videoIframeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <button type="button"
                        class="btn btn-link text-decoration-none text-white opacity-75-hover transition-all position-absolute end-0 top-0 z-index-20"
                        data-bs-dismiss="modal" aria-label="Close"><i class="ri-close-fill"></i></button>
                    <div id="player" class="modal-video-player plyr__video-embed">
                        <iframe src="" allowfullscreen allowtransparency allow="autoplay"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @vite('resources/js/vendor.bundle.js')
    @vite('resources/js/theme.bundle.js')
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
