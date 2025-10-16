@php
    use Illuminate\Support\Facades\Auth;
@endphp

<?php
// admin.blade.php
use App\Models\Contact;
use App\Models\Submission;
$unreadMessagesCount = Contact::where('read', false)->count();
$lastMessage = Contact::where('read', false)->orderBy('created_at', 'desc')->first();
$lastMessageTime = $lastMessage ? $lastMessage->created_at->diffForHumans() : 'Nenhuma mensagem';

$unreadSubmissionsCount = Submission::where('read', false)->count();
$lastSubmission = Submission::where('read', false)->orderBy('created_at', 'desc')->first();
$lastSubmissionTime = $lastSubmission ? $lastSubmission->created_at->diffForHumans() : 'Nenhuma submissão';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="AdminLTE v4 | Dashboard">
    <meta name="author" content="ColorlibHQ">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS.">
    <meta name="keywords"
        content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard">
    <link rel="icon" href="{{ asset('/img/amaer-ico.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css"
        integrity="sha256-dSokZseQNT08wYEWiz5iLI8QPlKxG+TswNRD8k35cpg=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css"
        integrity="sha256-Qsx5lrStHZyR9REqhUF8iQt73X06c8LGIUPzpOhwRrI=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
        integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css"
        integrity="sha384-Qsx5lrStHZyR9REqhUF8iQt73X06c8LGIUPzpOhwRrI=" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
    <!-- Cropper.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- CKEditor 5 -->
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    @vite('resources/css/adminlte.css')
        <script>
            // Inicializar CKEditor 5
            document.addEventListener('DOMContentLoaded', function() {
                if (document.querySelector('#inputConteudo')) {
                    ClassicEditor
                        .create(document.querySelector('#inputConteudo'), {
                            language: 'pt-br',
                            toolbar: {
                                items: [
                                    'heading', '|',
                                    'bold', 'italic', 'underline', '|',
                                    'link', 'insertImage', '|',
                                    'bulletedList', 'numberedList', '|',
                                    'outdent', 'indent', '|',
                                    'alignment', '|',
                                    'blockQuote', 'insertTable', '|',
                                    'undo', 'redo'
                                ]
                            },
                            image: {
                                toolbar: [
                                    'imageTextAlternative', 'imageStyle:inline', 'imageStyle:block', 'imageStyle:side'
                                ]
                            },
                            table: {
                                contentToolbar: [ 'tableColumn', 'tableRow', 'mergeTableCells' ]
                            }
                        })
                        .then(editor => {
                            window.editor = editor;
                            
                            // Aplicar altura mínima ao editor
                            const editorElement = document.querySelector('.ck-editor__editable');
                            if (editorElement) {
                                editorElement.style.minHeight = '300px';
                            }
                        })
                        .catch(error => {
                            console.error(error);
                        });
                }
            });
        </script>
    <style>
        /* CKEditor 5 - Altura mínima personalizada */
        .ck-editor__editable {
            min-height: 300px !important;
        }
        
        .ck-content {
            min-height: 280px !important;
        }
        
        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        
        .spinner {
            width: 100px;
            height: 100px;
            position: relative;
            perspective: 800px;
        }
        
        .spinner:before, .spinner:after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 10px solid transparent;
            border-top-color: #3498db;
            border-left-color: #3498db;
            animation: spin 2s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
        }
        
        .spinner:before {
            transform: rotateX(70deg);
        }
        
        .spinner:after {
            transform: rotateY(70deg);
            animation-delay: 0.4s;
        }
        
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        body {
            visibility: hidden;
        }

        body.loaded {
            visibility: visible;
        }

        /* AdminLTE Modern Styling */
        .app-header.navbar {
            border-bottom: 1px solid rgba(0,0,0,.125);
        }

        .app-sidebar {
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
        }

        .sidebar-brand .brand-link {
            background: rgba(0,0,0,.1);
            border-bottom: 1px solid rgba(255,255,255,.1);
        }

        .sidebar-menu .nav-link {
            color: #c2c7d0;
            transition: all 0.3s ease;
        }

        .sidebar-menu .nav-link:hover {
            background-color: rgba(255,255,255,.1);
            color: #fff;
        }

        .sidebar-menu .nav-link.active {
            background-color: #007bff;
            color: #fff;
            box-shadow: 0 2px 4px rgba(0,123,255,.3);
        }

        .sidebar-menu .nav-item.menu-open > .nav-link {
            background-color: rgba(255,255,255,.1);
        }

        .nav-treeview .nav-link {
            padding-left: 3rem;
            color: #adb5bd;
        }

        .nav-treeview .nav-link.active {
            background-color: rgba(0,123,255,.8);
            color: #fff;
        }

        .nav-icon {
            margin-right: 0.5rem;
            font-size: 1.1rem;
        }

        .navbar .nav-link {
            color: rgba(255,255,255,.8);
        }

        .navbar .nav-link:hover {
            color: #fff;
        }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-light">
    <div id="loading-screen">
        <div class="spinner"></div>
    </div>

    <div class="app-wrapper">
        <nav class="app-header navbar navbar-expand bg-primary">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item"> <a class="nav-link text-white" data-lte-toggle="sidebar" href="#" role="button"> <i
                                class="bi bi-list"></i> </a> </li>
             
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link text-white" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-bell-fill"></i>
                            <span
                                class="navbar-badge badge text-bg-warning">{{ $unreadMessagesCount + $unreadSubmissionsCount }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end"
                            style="max-height: 400px; overflow-y: auto;">
                            <span
                                class="dropdown-item dropdown-header">{{ $unreadMessagesCount + $unreadSubmissionsCount }}
                                Notificações</span>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('mensagens.index') }}" class="dropdown-item">
                                <i class="bi bi-envelope me-2"></i> {{ $unreadMessagesCount }} novas mensagens
                                <span class="float-end text-secondary fs-8">{{ $lastMessageTime }}</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('submissions.index') }}" class="dropdown-item">
                                <i class="bi bi-file-earmark-text me-2"></i> {{ $unreadSubmissionsCount }} novas
                                submissões
                                <span class="float-end text-secondary fs-8">{{ $lastSubmissionTime }}</span>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="#" data-lte-toggle="fullscreen"> <i
                                data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i> <i
                                data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none;"></i> </a>
                    </li>
                    <li class="nav-item dropdown user-menu"> <a href="#" class="nav-link dropdown-toggle text-white"
                            data-bs-toggle="dropdown">
                            @if(Auth::check())
                                <img src="/imagens/users/{{ Auth::user()->imagem }}" class="user-image rounded-circle shadow" alt="{{ Auth::user()->alt }}"> 
                                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span> 
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            @if(Auth::check())
                                <li class="user-header bg-body-secondary"> <img
                                        src="/imagens/users/{{ Auth::user()->imagem }}"
                                        class="rounded-circle bg-light shadow" alt="{{Auth::user()->alt}}">
                                    <p>
                                        {{ Auth::user()->name }} - {{ Auth::user()->cargo }} -  
                                        <small>Cadastro desde
                                            {{ Auth::user()->created_at->format('M, Y.') }}</small>
                                    </p>
                                </li>
                            @endif
                            <li class="user-footer"> 
                                <a href="{{ route('profile.edit') }}" class="btn btn-outline-success btn-flat">Perfil</a> 
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-flat float-end">Sair</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        <aside class="app-sidebar bg-dark shadow" data-bs-theme="dark">
            <div class="sidebar-brand"><a href="{{ route('admin') }}" class="brand-link"> <img
                        src="{{ asset('/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
                        class="brand-image opacity-75 shadow"><span class="brand-text fw-light">AMAER</span></a>
            </div>
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu"
                        data-accordion="false">

                        <!-- Funções e Permissões (Apenas Admins) -->
                        @if(auth()->user()->hasRole('Admin'))
                        <li class="nav-item {{ request()->routeIs('funcoes.index') || request()->routeIs('funcoes.create') || request()->routeIs('permissoes.create') || request()->routeIs('permissoes.index') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-shield-lock"></i>
                                <p>
                                    Funções e Permissões
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    @can('Criar Função')
                                    <a href="{{ route('funcoes.create') }}"
                                        class="nav-link {{ request()->routeIs('funcoes.create') ? 'active' : '' }}">
                                        <i class="nav-icon bi {{ request()->routeIs('funcoes.create') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Criar Função</p>
                                    </a>
                                    @endcan
                                </li>
                                <li class="nav-item">
                                    @can('Visualizar Função')
                                    <a href="{{ route('funcoes.index') }}"
                                        class="nav-link {{ request()->routeIs('funcoes.index') ? 'active' : '' }}">
                                        <i class="nav-icon bi {{ request()->routeIs('funcoes.index') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Listar Funções</p>
                                    </a>
                                    @endcan
                                </li>
                                <li class="nav-item">
                                    @can('Criar Permissão')
                                    <a href="{{ route('permissoes.create') }}"
                                        class="nav-link {{ request()->routeIs('permissoes.create') ? 'active' : '' }}">
                                        <i class="nav-icon bi {{ request()->routeIs('permissoes.create') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Criar Permissão</p>
                                    </a>
                                    @endcan
                                </li>
                                <li class="nav-item">
                                    @can('Visualizar Permissão')
                                    <a href="{{ route('permissoes.index') }}"
                                        class="nav-link {{ request()->routeIs('permissoes.index') ? 'active' : '' }}">
                                        <i class="nav-icon bi {{ request()->routeIs('permissoes.index') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Listar Permissões</p>
                                    </a>
                                    @endcan
                                </li>
                            </ul>
                        </li>
                        @endif

                        <!-- Notícias (Apenas Admins) -->
                        @if(auth()->user()->hasRole('Admin'))
                        <li class="nav-item {{ request()->routeIs('noticias.create') || request()->routeIs('noticias.index') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-newspaper"></i>
                                <p>
                                    Notícias
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    @can('Criar Notícia')
                                    <a href="{{ route('noticias.create') }}"
                                        class="nav-link {{ request()->routeIs('noticias.create') ? 'active' : '' }}">
                                        <i class="nav-icon bi {{ request()->routeIs('noticias.create') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Nova</p>
                                    </a>
                                    @endcan
                                </li>
                                <li class="nav-item">
                                    @can('Visualizar Notícia')
                                    <a href="{{ route('noticias.index') }}"
                                        class="nav-link {{ request()->routeIs('noticias.index') ? 'active' : '' }}">
                                        <i class="nav-icon bi {{ request()->routeIs('noticias.index') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Listagem</p>
                                    </a>
                                    @endcan
                                </li>
                            </ul>
                        </li>
                        @endif

                        <!-- Membros (Apenas Admins) -->
                        @if(auth()->user()->hasRole('Admin'))
                        <li class="nav-item {{ request()->routeIs('users.create') || request()->routeIs('users.index') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-people"></i>
                                <p>
                                    Membros
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    @can('Criar Membro')
                                    <a href="{{ route('users.create') }}"
                                        class="nav-link {{ request()->routeIs('users.create') ? 'active' : '' }}">
                                        <i class="nav-icon bi {{ request()->routeIs('users.create') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Novo</p>
                                    </a>
                                    @endcan
                                </li>
                                <li class="nav-item">
                                    @can('Visualizar Membro')
                                    <a href="{{ route('users.index') }}"
                                        class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                                        <i class="nav-icon bi {{ request()->routeIs('users.index') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Listagem</p>
                                    </a>
                                    @endcan
                                </li>
                            </ul>
                        </li>
                        @endif

                        <!-- Cargos (Apenas Admins) -->
                        @if(auth()->user()->hasRole('Admin'))
                        <li class="nav-item {{ request()->routeIs('cargos.*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-briefcase"></i>
                                <p>
                                    Cargos
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('cargos.create') }}"
                                        class="nav-link {{ request()->routeIs('cargos.create') ? 'active' : '' }}">
                                        <i class="nav-icon bi {{ request()->routeIs('cargos.create') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Novo</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('cargos.index') }}"
                                        class="nav-link {{ request()->routeIs('cargos.index') ? 'active' : '' }}">
                                        <i class="nav-icon bi {{ request()->routeIs('cargos.index') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Listagem</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        <!-- Galeria (Apenas Admins) -->
                        @if(auth()->user()->hasRole('Admin'))
                        <li class="nav-item {{ request()->routeIs('galeria.create') || request()->routeIs('galeria.indexAdmin') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-images"></i>
                                <p>
                                    Galeria
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    @can('Criar Galeria')
                                    <a href="{{ route('galeria.create') }}"
                                        class="nav-link {{ request()->routeIs('galeria.create') ? 'active' : '' }}">
                                        <i class="nav-icon bi {{ request()->routeIs('galeria.create') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Nova Mídia</p>
                                    </a>
                                    @endcan
                                </li>
                                <li class="nav-item">
                                    @can('Visualizar Galeria')
                                    <a href="{{ route('galeria.indexAdmin') }}"
                                        class="nav-link {{ request()->routeIs('galeria.indexAdmin') ? 'active' : '' }}">
                                        <i class="nav-icon bi {{ request()->routeIs('galeria.indexAdmin') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Listagem</p>
                                    </a>
                                    @endcan
                                </li>
                            </ul>
                        </li>
                        @endif

                        <!-- ATA -->
                        <li class="nav-item {{ request()->routeIs('admin.atas.create') || request()->routeIs('admin.atas.index') || request()->routeIs('admin.atas.*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-file-earmark-text"></i>
                                <p>
                                    ATA
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    @if(auth()->user()->hasRole('Admin'))
                                    <a href="{{ route('admin.atas.create') }}"
                                        class="nav-link {{ request()->routeIs('admin.atas.create') ? 'active' : '' }}">
                                        <i class="nav-icon bi {{ request()->routeIs('admin.atas.create') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Nova</p>
                                    </a>
                                    @endif
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.atas.index') }}"
                                        class="nav-link {{ request()->routeIs('admin.atas.index') || request()->routeIs('admin.atas.show') || request()->routeIs('admin.atas.edit') ? 'active' : '' }}">
                                        <i class="nav-icon bi {{ request()->routeIs('admin.atas.index') || request()->routeIs('admin.atas.show') || request()->routeIs('admin.atas.edit') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Listagem</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Editar Páginas -->
                        <li class="nav-item">
                            @can('Criar Frase')
                            <a href="{{ route('admin.frase_inicio.editar') }}"
                                class="nav-link {{ request()->routeIs('admin.frase_inicio.editar') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-pencil-square"></i>
                                <p>Editar Páginas</p>
                            </a>
                            @endcan
                        </li>

                        <!-- Documentos -->
                        <li class="nav-item {{ request()->routeIs('documentos.create') || request()->routeIs('documentos.index') || request()->routeIs('documentos.*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-file-earmark-pdf"></i>
                                <p>
                                    Documentos
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('documentos.create') }}"
                                        class="nav-link {{ request()->routeIs('documentos.create') ? 'active' : '' }}">
                                        <i class="nav-icon bi {{ request()->routeIs('documentos.create') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Novo</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('documentos.index') }}"
                                        class="nav-link {{ request()->routeIs('documentos.index') || request()->routeIs('documentos.show') || request()->routeIs('documentos.edit') ? 'active' : '' }}">
                                        <i class="nav-icon bi {{ request()->routeIs('documentos.index') || request()->routeIs('documentos.show') || request()->routeIs('documentos.edit') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Listagem</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Contato -->
                        <li class="nav-item">
                            @can('Visualizar Mensagem')
                            <a href="{{ route('mensagens.index') }}" 
                               class="nav-link {{ request()->routeIs('mensagens.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-envelope"></i>
                                <p>Contato</p>
                            </a>
                            @endcan
                        </li>

                        <!-- Editar Perfil (Todos os usuários) -->
                        <li class="nav-item">
                            <a href="{{ route('profile.edit') }}" 
                               class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-person-gear"></i>
                                <p>Editar Perfil</p>
                            </a>
                        </li>

                        <!-- Alterar Senha (Todos os usuários) -->
                        <li class="nav-item">
                            <a href="{{ route('password.custom.edit') }}" 
                               class="nav-link {{ request()->routeIs('password.custom.edit') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-key-fill"></i>
                                <p>Alterar Senha</p>
                            </a>
                        </li>

                        <!-- Cartão do Associado (Todos os usuários) -->
                        <li class="nav-item">
                            <a href="{{ route('cartao-associado') }}" 
                               class="nav-link {{ request()->routeIs('cartao-associado') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-credit-card"></i>
                                <p>Cartão do Associado</p>
                            </a>
                        </li>

                    </ul>
                </nav>
            </div>

        </aside>
        <main class="app-main">
            @yield('content')
        </main>
        <footer class="app-footer">
            <div class="float-end d-none d-sm-inline">Anything you want</div><strong>
                Copyright &copy; 2014-2024&nbsp;
                <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
            </strong>
            All rights reserved.
        </footer>
    </div>
    <style>
        .focus-ring-green:focus {
            border-color: green;
            box-shadow: 0 0 0 0.25rem rgba(0, 128, 0, 0.25);
        }

        .focus-ring-orange:focus {
            border-color: orange;
            box-shadow: 0 0 0 0.25rem rgba(255, 165, 0, 0.25);
        }

        .focus-ring-red:focus {
            border-color: red;
            box-shadow: 0 0 0 0.25rem rgba(255, 0, 0, 0.25);
        }
    </style>
    <style>
        .alert {
            position: fixed;
            top: 110px;
            left: 30%;
            transform: translateX(-50%);
            padding: 1rem;
            margin: 0;
            border: 1px solid transparent;
            border-radius: .20rem;
            z-index: 1050;
        }

        .progress-bar-container {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background-color: #f1f1f1;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            width: 0%;
            background-color: #28a745;
            transition: width 0.5s linear;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var alert = document.getElementById('alert');
            var progressBar = document.getElementById('progress-bar');

            if (alert && progressBar) {
                var duration = 5000;
                var interval = 10;
                var progress = 0;

                function updateProgressBar() {
                    progress += (interval / duration) * 100;
                    progressBar.style.width = progress + '%';
                    if (progress >= 100) {
                        clearInterval(progressInterval);
                        setTimeout(function () {
                            alert.classList.remove('show');
                            alert.classList.add('fade');
                        }, 500);
                    }
                }
                var progressInterval = setInterval(updateProgressBar, interval);
            }
        });
    </script>

    <script>
        function updateCharacterCount() {
            const textarea = document.getElementById('descricao');
            const charCount = document.getElementById('charCount');
            const maxLength = 520;
            const currentLength = textarea.value.length;

            charCount.textContent = `${currentLength}/${maxLength}`;

            textarea.classList.remove('focus-ring-green', 'focus-ring-orange', 'focus-ring-red');

            if (currentLength < maxLength / 2) {
                textarea.classList.add('focus-ring-green');
            } else if (currentLength < maxLength) {
                textarea.classList.add('focus-ring-orange');
            } else {
                textarea.classList.add('focus-ring-red');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const textarea = document.getElementById('descricao');
            textarea.addEventListener('input', updateCharacterCount);
            updateCharacterCount();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js"
        integrity="sha256-H2VM7BKda+v2Z4+DRy69uknwxjyDRhszjXFhsL4gD3w=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha256-whL0tQWoY1Ku1iskqPFvmZ+CHsvmRWx/PIoEvIeWh4I=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha256-YMa+wAM6QkVyz999odX7lPRxkoYAan8suedu4k2Zur8=" crossorigin="anonymous"></script>
        @vite('resources/js/adminlte.js')
        @vite('resources/js/menu.js')
            <script>
                const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
                const Default = {
                    scrollbarTheme: "os-theme-light",
                    scrollbarAutoHide: "leave",
                    scrollbarClickScroll: true,
                };
                document.addEventListener("DOMContentLoaded", function () {
                    const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
                    if (sidebarWrapper && typeof OverlayScrollbarsGlobal ? .OverlayScrollbars !== "undefined") {
                        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                            scrollbars: {
                                theme: Default.scrollbarTheme,
                                autoHide: Default.scrollbarAutoHide,
                                clickScroll: Default.scrollbarClickScroll,
                            },
                        });
                    }
                });
            </script>
            <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"
                integrity="sha256-ipiJrswvAR4VAx/th+6zWsdeYmVae0iJuiR+6OqHJHQ=" crossorigin="anonymous"></script>
            <!-- sortablejs -->
            <script>
                const connectedSortables =
                    document.querySelectorAll(".connectedSortable");
                connectedSortables.forEach((connectedSortable) => {
                    let sortable = new Sortable(connectedSortable, {
                        group: "shared",
                        handle: ".card-header",
                    });
                });

                <script >
                    const cardHeaders = document.querySelectorAll(
                        ".connectedSortable .card-header",
                    );
                cardHeaders.forEach((cardHeader) => {
                    cardHeader.style.cursor = "move";
                });
            </script>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
                integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"
                integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y=" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"
                integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY=" crossorigin="anonymous"></script>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
            <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
            <script>
                $(document).ready(function () {
                    $('#inputCpf').mask('000.000.000-00', {
                        reverse: true
                    });
                });
            </script>
            <script>
                document.querySelector('#cancel-button').addEventListener('click', function () {
                    $('#modal').modal('hide');
                    document.querySelector('#inputImagem').value = '';
                });
            </script>
            <script>
                document.getElementById('crop').addEventListener('click', function () {
                    document.getElementById('croppedImageContainer').style.display = 'block';
                });
            </script>
            <script>
                document.getElementById('inputImagem').addEventListener('change', function () {
                    if (this.files.length > 0) {
                        var file = this.files[0];
                        var done = function (url) {
                            document.getElementById('image').src = url;
                            $('#modal').modal('show');
                        };

                        if (URL) {
                            done(URL.createObjectURL(file));
                        } else if (FileReader) {
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                done(reader.result);
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                });
            </script>

            <script>
                document.getElementById('inputImagem').addEventListener('change', function (event) {
                    const [file] = event.target.files;
                    if (file) {
                        const preview = document.getElementById('newImagePreview');
                        preview.innerHTML =
                            `<p class="mt-2"><strong>Nova imagem:</strong></p><img src="${URL.createObjectURL(file)}" width="160px" class="mt-2">`;
                    }
                });
            </script>

    <script>
        window.addEventListener('load', function() {
            // Aguarda um pequeno delay para garantir que todos os recursos estejam carregados
            setTimeout(function() {
                document.body.classList.add('loaded');
                document.getElementById('loading-screen').style.display = 'none';
            }, 500);
        });
    </script>
</body>

</html>
