@extends('layouts.admin')
@section('title')
Notícia - Cadastro
@endsection
@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Notícia - Cadastro</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Notícia - Cadastro
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container pt-2 d-flex justify-content-center">
    <div class="card-body p-4 p-lg-5 rounded-4 shadow-sm bg-white" style="max-width: 1000px; width: 100%;">
        <header class="text-center mb-4">
            <h2 class="fs-4 fw-medium mb-3">{{ __('Criar Nova Notícia') }}</h2>
            <p class="text-muted">{{ __("Preencha as informações para publicar uma nova notícia.") }}</p>
        </header>

        <form action="{{ route('noticias.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Informações Básicas -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informações Básicas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="inputTitulo" class="form-label"><strong>Título:</strong></label>
                                <input type="text" class="form-control @error('titulo') is-invalid @enderror" name="titulo" id="inputTitulo" placeholder="Título da notícia..." value="{{ old('titulo') }}">
                                @error('titulo')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="inputCategoria" class="form-label"><strong>Categoria:</strong></label>
                                <input type="text" class="form-control @error('categoria') is-invalid @enderror" name="categoria" id="inputCategoria" placeholder="Categoria..." value="{{ old('categoria') }}">
                                @error('categoria')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="inputAutor" class="form-label"><strong>Autor:</strong></label>
                        <input type="text" class="form-control @error('autor') is-invalid @enderror" name="autor" id="inputAutor" placeholder="Nome do autor..." value="{{ old('autor') }}">
                        @error('autor')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Conteúdo -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Conteúdo</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="inputConteudo" class="form-label"><strong>Conteúdo:</strong></label>
                        <textarea class="form-control @error('conteudo') is-invalid @enderror" style="height: 400px" name="conteudo" id="inputConteudo" placeholder="Escreva o conteúdo da notícia...">{{ old('conteudo') }}</textarea>
                        @error('conteudo')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Imagem de Capa -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-image"></i> Imagem de Capa</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputImagem" class="form-label"><strong>Capa:</strong></label>
                                <input type="file" name="imagem" class="form-control @error('imagem') is-invalid @enderror" id="inputImagem" accept="image/*">
                                <div class="form-text">Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB.</div>
                                @error('imagem')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inputAlt" class="form-label"><strong>Descrição da Imagem (Alt):</strong></label>
                                <input type="text" class="form-control @error('alt') is-invalid @enderror" name="alt" id="inputAlt" placeholder="Descreva a imagem..." value="{{ old('alt') }}">
                                <div class="form-text">Descrição para acessibilidade e SEO.</div>
                                @error('alt')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <a href="{{ route('noticias.index') }}" class="btn btn-secondary me-md-2">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Publicar Notícia
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 