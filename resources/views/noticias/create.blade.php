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
<div class="container d-flex justify-content-center">
    <div class="card-body" style="max-width: 600px;">
        <form action="{{ route('noticias.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="inputTitulo" class="form-label"><strong>Titulo:</strong></label>
                <input type="text" class="form-control" name="titulo" id="inputTitulo" placeholder="Titulo...">
                @error('titulo')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="inputConteudo" class="form-label"><strong>Conteudo:</strong></label>
                <textarea class="form-control" style="height: 600px" name="conteudo" id="inputConteudo"
                    placeholder="Conteudo..."></textarea>
                @error('conteudo')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="inputAutor" class="form-label"><strong>Autor:</strong></label>
                <input type="text" class="form-control" name="autor" id="inputAutor" placeholder="Autor...">
                @error('autor')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="inputImagem" class="form-label"><strong>Capa:</strong></label>
                <input type="file" name="imagem" class="form-control @error('imagem') inválida @enderror"
                    id="inputImagem">
                @error('imagem')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="inputAlt" class="form-label"><strong>Alt:</strong></label>
                <input type="text" class="form-control @error('alt') inválido @enderror" name="alt" id="inputAlt"
                    placeholder="Descreva a capa...">
                @error('alt')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="inputCategoria" class="form-label"><strong>Categoria:</strong></label>
                <input type="text" class="form-control @error('categoria') inválida @enderror" name="categoria"
                    id="inputCategoria" placeholder="Categoria...">
                @error('categoria')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-outline-success">
                    <i class="fas fa-plus"></i> Salvar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 