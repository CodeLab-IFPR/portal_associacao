@extends('layouts.admin')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Editar Notícia</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('noticias.index') }}">Notícia</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container d-flex justify-content-center">
    <div class="card-body" style="max-width: 600px;">
        <form action="{{ route('noticias.update', $noticia->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="inputTitulo" class="form-label"><strong>Titulo:</strong></label>
                <input type="text" class="form-control" name="titulo" id="inputTitulo" placeholder="Titulo..." value="{{ $noticia->titulo }}">
                @error('titulo')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="inputConteudo" class="form-label"><strong>Conteudo:</strong></label>
                <textarea class="form-control @error('conteudo') inválida @enderror" style="height: 600px" name="conteudo" id="inputConteudo" placeholder="Conteudo...">{{ $noticia->conteudo }}</textarea>
                @error('conteudo')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="inputAutor" class="form-label"><strong>Autor:</strong></label>
                <input type="text" class="form-control" name="autor" id="inputAutor" placeholder="Autor..." value="{{ $noticia->autor }}">
                @error('autor')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="inputImagem" class="form-label"><strong>Imagem:</strong></label>
                <input type="file" name="imagem" class="form-control @error('imagem') is-invalid @enderror image" id="inputImagem">
                @if($noticia->imagem)
                    <p class="mt-2"><strong>Imagem atual:</strong></p>
                    <img src="/imagens/noticias/{{ $noticia->imagem }}" width="160px" class="mt-2">
                @endif
                @error('imagem')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
                <div id="newImagePreview" class="mt-2"></div>
            </div>
            <div class="mb-3">
                <label for="inputAlt" class="form-label"><strong>Alt:</strong></label>
                <input type="text" class="form-control @error('alt') inválido @enderror" name="alt" value="{{ $noticia->alt }}" id="inputAlt" placeholder="Descreva a capa...">
                @error('alt')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="inputCategoria" class="form-label"><strong>Categoria:</strong></label>
                <input type="text" class="form-control @error('categoria') inválida @enderror" name="categoria" value="{{ $noticia->categoria }}" id="inputCategoria" placeholder="Categoria...">
                @error('categoria')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-floppy-disk"></i> Atualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection