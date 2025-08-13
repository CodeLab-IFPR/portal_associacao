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
    <link rel="icon" href="{{ asset('/img/codelab-logo-ico.png') }}" type="image/png">
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
    <script src="https://cdn.tiny.cloud/1/i6174a4p21k3bvgofjdjglzvdfxrle8qza1n62srherxw93i/tinymce/7/tinymce.min.js">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
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
    <script src="https://cdn.tiny.cloud/1/i6174a4p21k3bvgofjdjglzvdfxrle8qza1n62srherxw93i/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    @vite('resources/css/adminlte.css')
        <script>
            tinymce.init({
                selector: '#inputConteudo',
                language: 'pt_BR',
                directionality: 'ltr',
                toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media | forecolor backcolor emoticons',
                plugins: [
                    'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor',
                    'pagebreak',
                    'searchreplace', 'wordcount', 'visualblocks', 'visualchars', 'code', 'fullscreen',
                    'insertdatetime',
                    'media', 'table', 'emoticons', 'help'
                ],
            });
        </script>
    <style>
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
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div id="loading-screen">
        <div class="spinner"></div>
    </div>

    <div class="app-wrapper">
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item"> <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"> <i
                                class="bi bi-list"></i> </a> </li>
                    <li class="nav-item d-none d-md-block"> <a href="{{ route('home') }}"
                            class="nav-link">Home</a> </li>
                    <li class="nav-item d-none d-md-block"> <a href="{{ route('contact') }}"
                            class="nav-link">Contato</a> </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#">
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
                    <li class="nav-item dropdown user-menu"> <a href="#" class="nav-link dropdown-toggle"
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
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <div class="sidebar-brand"><a href="{{ route('admin') }}" class="brand-link"> <img
                        src="{{ asset('/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
                        class="brand-image opacity-75 shadow"><span class="brand-text fw-light">AdminLTE 4</span></a>
            </div>
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu"
                        data-accordion="false">

                        <li
                            class="nav-item {{ request()->routeIs('funcoes.index') || request()->routeIs('funcoes.create') || request()->routeIs('permissoes.create') || request()->routeIs('permissoes.index') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link ">
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
                                        <i
                                            class="nav-icon bi {{ request()->routeIs('funcoes.create') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Criar Função</p>
                                    </a>
                                    @endcan
                                </li>
                                <li class="nav-item">
                                    @can('Visualizar Função')
                                    <a href="{{ route('funcoes.index') }}"
                                        class="nav-link {{ request()->routeIs('funcoes.index') ? 'active' : '' }}">
                                        <i
                                            class="nav-icon bi {{ request()->routeIs('funcoes.index') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Listar Funções</p>
                                    </a>
                                    @endcan
                                </li>
                                <li class="nav-item">
                                    @can('Criar Permissão')
                                    <a href="{{ route('permissoes.create') }}"
                                        class="nav-link {{ request()->routeIs('permissoes.create') ? 'active' : '' }}">
                                        <i
                                            class="nav-icon bi {{ request()->routeIs('permissoes.create') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Criar Permissão</p>
                                    </a>
                                    @endcan
                                </li>
                                <li class="nav-item">
                                    @can('Visualizar Permissão')
                                    <a href="{{ route('permissoes.index') }}"
                                        class="nav-link {{ request()->routeIs('permissoes.index') ? 'active' : '' }}">
                                        <i
                                            class="nav-icon bi {{ request()->routeIs('permissoes.index') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Listar Permissões</p>
                                    </a>
                                    @endcan
                                </li>
                            </ul>
                        </li>
                        <li
                            class="nav-item {{ request()->routeIs('noticias.create') || request()->routeIs('users.create') || request()->routeIs('parceiros.create') || request()->routeIs('galeria.create') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-journal-plus"></i>
                                <p>
                                    Cadastro
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    @can('Criar Notícia')
                                    <a href="{{ route('noticias.create') }}"
                                        class="nav-link {{ request()->routeIs('noticias.create') ? 'active' : '' }}">
                                        <i
                                            class="nav-icon bi {{ request()->routeIs('noticias.create') ? 'bi-play-fill' : 'bi-play' }}"></i>
                                        <p>Nova Notícia</p>
                                    </a>
                                    @endcan
                                </li>
                                <li class="nav-item">
                                    @can('Criar Membro')
                                    <a href="{{ route('users.create') }}"
                                        class="nav-link  {{ request()->routeIs('users.create') ? 'active' : '' }}">
                                        <i
                                            class="nav-icon bi {{ request()->routeIs('users.create') ? 'bi-play-fill' : 'bi-play' }}"></i>
                                        <p>Novo Membro</p>
                                    </a>
                                    @endcan
                                </li>
                                <li class="nav-item">
                                    @can('Criar Parceiro')
                                    <a href="{{ route('parceiros.create') }}"
                                        class="nav-link  {{ request()->routeIs('parceiros.create') ? 'active' : '' }}">
                                        <i
                                            class="nav-icon bi {{ request()->routeIs('parceiros.create') ? 'bi-play-fill' : 'bi-play' }}"></i>
                                        <p>Novo Parceiro</p>
                                    </a>
                                    @endcan
                                </li>
                                <li class="nav-item">
                                    @can('Criar Galeria')
                                    <a href="{{ route('galeria.create') }}"
                                        class="nav-link  {{ request()->routeIs('galeria.create') ? 'active' : '' }}">
                                        <i
                                            class="nav-icon bi {{ request()->routeIs('galeria.create') ? 'bi-play-fill' : 'bi-play' }}"></i>
                                        <p>Galeria - Nova Mídia</p>
                                    </a>
                                    @endcan
                                </li>
                            </ul>
                        </li>

                        <li
                            class="nav-item {{ request()->routeIs('noticias.index') || request()->routeIs('users.index') || request()->routeIs('parceiros.index') || request()->routeIs('galeria.indexAdmin') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-journal-text"></i>
                                <p>
                                    Lista
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    @can('Visualizar Notícia')
                                    <a href="{{ route('noticias.index') }}"
                                        class="nav-link {{ request()->routeIs('noticias.index') ? 'active' : '' }}">
                                        <i
                                            class="nav-icon bi {{ request()->routeIs('noticias.index') ? 'bi-play-fill' : 'bi-play' }}"></i>
                                        <p>Noticias</p>
                                    </a>
                                    @endcan
                                </li>
                                <li class="nav-item">
                                    @can('Visualizar Membro')
                                    <a href="{{ route('users.index') }}"
                                        class="nav-link  {{ request()->routeIs('users.index') ? 'active' : '' }}">
                                        <i
                                            class="nav-icon bi {{ request()->routeIs('users.index') ? 'bi-play-fill' : 'bi-play' }}"></i>
                                        <p>Membro</p>
                                    </a>
                                    @endcan
                                </li>
                                <li class="nav-item">
                                    @can('Visualizar Parceiro')
                                    <a href="{{ route('parceiros.index') }}"
                                        class="nav-link {{ request()->routeIs('parceiros.index') ? 'active' : '' }}">
                                        <i
                                            class="nav-icon bi {{ request()->routeIs('parceiros.index') ? 'bi-play-fill' : 'bi-play' }} "></i>
                                        <p>Parceiro</p>
                                    </a>
                                    @endcan
                                </li>
                                <li class="nav-item">
                                    @can('Visualizar Galeria')
                                    <a href="{{ route('galeria.indexAdmin') }}"
                                        class="nav-link {{ request()->routeIs('galeria.indexAdmin') ? 'active' : '' }}">
                                        <i
                                            class="nav-icon bi {{ request()->routeIs('galeria.indexAdmin') ? 'bi-play-fill' : 'bi-play' }} "></i>
                                        <p>Galeria</p>
                                    </a>
                                    @endcan
                                </li>
                            </ul>
                        </li>
                        <li
                            class="nav-item {{ request()->routeIs('certificados.index') || request()->routeIs('certificados.create') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-award"></i>
                                <p>
                                    Certificado
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    @can('Criar Certificado')
                                    <a href="{{ route('certificados.create') }}"
                                        class="nav-link {{ request()->routeIs('certificados.create') ? 'active' : '' }}">
                                        <i
                                            class="nav-icon bi {{ request()->routeIs('certificados.create') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Novo Certificado</p>
                                    </a>
                                    @endcan
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    @can('Visualizar Certificado')
                                    <a href="{{ route('certificados.index') }}"
                                        class="nav-link {{ request()->routeIs('certificados.index') ? 'active' : '' }}">
                                        <i
                                            class="nav-icon bi {{ request()->routeIs('certificados.index') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Todos Certificados</p>
                                    </a>
                                    @endcan
                                </li>
                            </ul>
                        <li class="nav-item">
                            @can('Criar Frase')
                            <a href="{{ route('admin.frase_inicio.editar') }}"
                                class="nav-link {{ request()->routeIs('admin.frase_inicio.editar') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-pencil-square"></i>
                                <p>Editar Páginas</p>
                            </a>
                            @endcan
                        </li>
                        </li>
                        <li class="nav-item">
                            @can('Visualizar Mensagem')
                            <a href="{{ route('mensagens.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-envelope"></i>
                                <p>Contato</p>
                            </a>
                            @endcan
                        </li>
                        <li class="nav-item">
                            @can('Visualizar Submissão')
                            <a href="{{ route('submissions.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-file-earmark-text"></i>
                                <p>Submissões</p>
                            </a>
                            @endcan
                        </li>
                        <li
                            class="nav-item {{ request()->routeIs('projetos.index') || request()->routeIs('projetos.create') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-folder"></i>
                                <p>
                                    Projetos
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    @can('Criar Projeto')
                                    <a href="{{ route('projetos.create') }}"
                                        class="nav-link  {{ request()->routeIs('projetos.create') ? 'active' : '' }}">
                                        <i
                                            class="nav-icon bi {{ request()->routeIs('projetos.create') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Criar Projeto</p>
                                    </a>
                                    @endcan
                                </li>
                                <li class="nav-item">
                                    @can('Visualizar Projeto')
                                    <a href="{{ route('projetos.index') }}"
                                        class="nav-link {{ request()->routeIs('projetos.index') ? 'active' : '' }}">
                                        <i
                                            class="nav-icon bi {{ request()->routeIs('projetos.index') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Listar Projetos</p>
                                    </a>
                                    @endcan
                                </li>
                                <li class="nav-item">
                                    @can('Criar Projeto')
                                    <a href="{{ route('tags.index') }}"
                                        class="nav-link {{ request()->routeIs('tags.index') ? 'active' : '' }}">
                                        <i
                                            class="nav-icon bi {{ request()->routeIs('tags.index') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Tags</p>
                                    </a>
                                    @endcan
                                </li>
                            </ul>
                        </li>
                        <li
                            class="nav-item {{ request()->routeIs('servicos.index') || request()->routeIs('servicos.create') ? 'menu-open' : '' }}">

                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-briefcase"></i>
                                <p>
                                    Serviços
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                @can('Criar Serviço')
                                <a href="{{ route('servicos.create') }}"
                                    class="nav-link {{ request()->routeIs('servicos.create') ? 'active' : '' }}">
                                    <i
                                        class="nav-icon bi {{ request()->routeIs('servicos.create') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                    <p>Criar Serviço</p>
                                </a>
                                @endcan
                                </li>
                                <li class="nav-item">
                                    @can('Visualizar Serviço')
                                    <a href="{{ route('servicos.index') }}"
                                        class="nav-link {{ request()->routeIs('servicos.index') ? 'active' : '' }}">
                                        <i
                                            class="nav-icon bi {{ request()->routeIs('servicos.index') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Listar Serviços</p>
                                    </a>
                                    @endcan
                                </li>
                            </ul>
                        </li>
                        <li
                            class="nav-item {{ request()->routeIs('lancamentos.index') || request()->routeIs('lancamentos.create') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-calendar-event"></i>
                                <p>
                                    Lançamentos
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    @can('Criar Lançamento')
                                    <a href="{{ route('lancamentos.create') }}"
                                        class="nav-link {{ request()->routeIs('lancamentos.create') ? 'active' : '' }}">
                                        <i
                                            class="nav-icon bi {{ request()->routeIs('lancamentos.create') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Criar Lançamento</p>
                                    </a>
                                    @endcan
                                </li>
                                <li class="nav-item">
                                    @can('Visualizar Lançamento')
                                    <a href="{{ route('lancamentos.index') }}"
                                        class="nav-link {{ request()->routeIs('lancamentos.index') ? 'active' : '' }}">
                                        <i
                                            class="nav-icon bi {{ request()->routeIs('lancamentos.index') ? 'bi-circle-fill' : 'bi-circle' }}"></i>
                                        <p>Listar Lançamentos</p>
                                    </a>
                                    @endcan
                                </li>
                            </ul>
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

                <
                script >
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
                tinymce.init({
                    selector: '#inputConteudo',
                    language: 'pt_BR',
                    directionality: 'ltr',
                    toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media | forecolor backcolor emoticons',
                    plugins: [
                        'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor',
                        'pagebreak',
                        'searchreplace', 'wordcount', 'visualblocks', 'visualchars', 'code', 'fullscreen',
                        'insertdatetime',
                        'media', 'table', 'emoticons', 'help'
                    ],
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
