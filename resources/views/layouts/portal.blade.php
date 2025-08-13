<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portal CodeLab - Submissão de Demandas, Certificados e mais.">
    <meta name="author" content="Cadu e João">
    <meta name="keywords" content="">

    <link rel="apple-touch-icon" sizes="180x180" src="{{ asset('img/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/codelab-logo-ico.png')  }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/codelab-logo-ico.png')  }}">
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
    </style>
    
    @vite('resources/css/libs.bundle.css')    
    @vite('resources/css/theme.bundle.css')
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
        <a class="navbar-brand d-flex align-items-center lh-1 me-1 transition-opacity opacity-75-hover" href="{{ route('home') }}">
            <span class="f-w-8 d-block text-success mb-1 me-1">
                <img class="img-fluid mx-auto d-block" src="{{ asset('img/codelab-logo-ico.png') }}" alt="">
            </span>
            <span class="fw-bold text-body text-center fs-6">CodeLab</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="ri-menu-line"></i>
        </button>    
        <div class="collapse navbar-collapse justify-content-evenly" id="navbarSupportedContent">
            <ul class="navbar-nav" style="gap: 0.3rem;"> 
                <li class="nav-item dropdown position-static ">
                    <a class="nav-link dropdown-toggle me-lg-0 ms-lg-0" href="#" role="button" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false" style="font-size: 1.2rem;">
                        <i class="ri-award-line me-2"></i>
                        <span>Certificação</span>
                    </a>
                    <div class="dropdown-menu dropdown-megamenu">
                        <div class="container">
                            <div class="row py-lg-5 gx-8">
                                <div class="col-auto me-4 mb-4 col-lg-4 d-flex align-items-start">
                                    <span class="f-w-16 d-block text-primary me-4 d-none d-lg-flex">
                                        <svg class="w-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline opacity=".4" points="2 12 12 17 22 12"></polyline></svg>
                                    </span>
                                    <div class="position-relative">
                                        <h6 class="dropdown-heading">Certificado</h6>
                                        <p class="text-muted">Emita seu certificado de participação</p>
                                        <a href="{{ route('certificados.emitir') }}" class="fw-medium fs-7 text-decoration-none link-cover">Acessar &rarr;</a>
                                    </div>
                                </div>
                                <div class="col-auto me-4 mb-4 col-lg-4 d-flex align-items-start">
                                    <span class="f-w-16 d-block text-primary me-4 d-none d-lg-flex">
                                        <svg class="w-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect opacity=".3" x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="14" x2="23" y2="14"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="14" x2="4" y2="14"></line></svg>
                                    </span>
                                    <div class="position-relative">
                                        <h6 class="dropdown-heading">Validar Certificado</h6>
                                        <p class="text-muted">Valide certificados de participação.</p>
                                        <a href="{{ route('certificados.validar') }}" class="fw-medium fs-7 text-decoration-none link-cover">Acessar &rarr;</a>
                                    </div>
                                </div>                              
                            </div>
                        </div>
                    </div>
                </li>    
                <li class="nav-item">
                    <a class="nav-link me-lg-0 ms-lg-0" href="{{ route('projeto.indexPublic') }}" style="font-size: 1.2rem;">
                        <i class="ri-newspaper-line me-2"></i>
                        <span>Projetos</span>
                    </a>
                </li>
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
                    <a class="nav-link me-lg-0 ms-lg-0" href="{{ route('submission') }}" style="font-size: 1.2rem;">
                        <i class="ri-upload-line me-2"></i>
                        <span>Submissão</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-lg-0 ms-lg-0" href="{{ route('galeria.indexPublic') }}" style="font-size: 1.2rem;">
                        <i class="ri-gallery-line me-2"></i>
                        <span>Galeria</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<main class="mt-0 ">
    @yield('content')
</main>
<footer class="bg-dark pt-4 pb-8">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
            <a class="d-flex align-items-center lh-1 text-white transition-opacity opacity-50-hover text-decoration-none mb-4 mb-md-0"
                href="#">
                <span class="f-w-7 d-block text-success me-2">
                <img class="img-fluid d-table mx-auto bg-white rounded-1" src="{{ asset('img/codelab-logo-ico.png') }}" alt="">
                </span>
                <span class="fw-bold">CodeLab</span>
            </a>    
        </div>
        <div class="d-flex flex-wrap justify-content-between mt-5 mt-lg-7">    
            <div class="w-100 w-sm-50 w-lg-auto mb-4 mb-lg-0">
                <h6 class="text-uppercase fs-xs fw-bolder tracking-wider text-white opacity-50">Portal</h6>
                <ul class="list-unstyled footer-nav">
                    <li><a href="{{ route('about') }}">Sobre Nós</a></li>
                    <li><a href="#">Junte-se</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Últimas Noticias</a></li>
                </ul>
            </div>
            <div class="w-100 w-sm-50 w-lg-auto mb-4 mb-lg-0">
                <h6 class="text-uppercase fs-xs fw-bolder tracking-wider text-white opacity-50">Navegação</h6>
                <ul class="list-unstyled footer-nav">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('contact') }}">Contato</a></li>
                </ul>
            </div>
            <div class="w-100 w-sm-50 w-lg-auto mb-4 mb-lg-0">
                <h6 class="text-uppercase fs-xs fw-bolder tracking-wider text-white opacity-50">Termos Legais</h6>
                <ul class="list-unstyled footer-nav">
                    <li><a href="#">Política de Privacidade</a></li>
                    <li><a href="#">Termos & Condições</a></li>
                    <li><a href="#">LGPD</a></li>
                    <li><a href="https://ifpr.edu.br/paranavai/" target="_blank">IFPR</a></li>
                </ul>
            </div>    
        </div>
    </div>
    <div class="container">
        <div class="border-top pt-6 mt-7 border-white-10 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <span class="small text-white opacity-50 mb-2 mb-md-0">Todos os direitos reservados &copy IFPR 2024 e Sigma 2021</span>
            <span class="small text-white opacity-50">Termos de Serviço  |  Política de Segurança</span>
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
</body>
</html>
