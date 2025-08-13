@extends('layouts.admin')

<!-- Titulo -->
@section('title')
Parceiros - Edição
@endsection
<!-- Titulo -->

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Parceiro - Edição</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container d-flex justify-content-center">
    <div class="card-body" style="max-width: 600px;">
        <form action="{{ route('parceiros.update',$parceiro->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="inputNome" class="form-label"><strong>Nome:</strong></label>
                <input type="text" name="nome" value="{{ $parceiro->nome }}"
                    class="form-control @error('nome') inválido @enderror" id="inputNome" placeholder="Nome...">
                @error('nome')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="inputEmail" class="form-label"><strong>E-mail:</strong></label>
                <input type="email" name="email" value="{{ $parceiro->email }}"
                    class="form-control @error('email') inválido @enderror" id="inputEmail" placeholder="E-mail...">
                @error('email')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="inputLink" class="form-label"><strong>URL:</strong></label>
                <input type="url" name="link" value="{{ $parceiro->link }}"
                    class="form-control @error('link') inválido @enderror" id="inputLink" placeholder="URL...">
                @error('link')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="inputImagem" class="form-label"><strong>Imagem:</strong></label>
                <input type="file" name="imagem" class="form-control @error('imagem') is-invalid @enderror image" id="inputImagem">
                @if($parceiro->imagem)
                    <p class="mt-2"><strong>Imagem atual:</strong></p>
                    <img src="/imagens/parceiros/{{ $parceiro->imagem }}" width="160px" class="mt-2">
                @endif
                @error('imagem')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
                <div id="newImagePreview" class="mt-2"></div>
            </div>

            <div class="mb-3">
                <label for="inputAlt" class="form-label"><strong>Alt:</strong></label>
                <input type="text" name="alt" value="{{ $parceiro->alt }}"
                    class="form-control @error('alt') inválida @enderror" id="inputAlt"
                    placeholder="Descrição da imagem...">
                @error('alt')
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