@extends('layouts.admin')

<!-- Titulo -->
@section('title')
Editar Frases
@endsection
<!-- Titulo -->

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Editar Frases</h3>
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
        <form action="{{ route('admin.frase_inicio.atualizar') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="titulo" class="form-label"><strong>Título Principal:</strong></label>
                <input type="text" name="titulo" id="titulo" class="form-control @error('titulo') is-invalid @enderror" value="{{ old('titulo', $fraseInicio->titulo ?? 'AMAER') }}" required>
                @error('titulo')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="subtitulo" class="form-label"><strong>Subtítulo:</strong></label>
                <input type="text" name="subtitulo" id="subtitulo" class="form-control @error('subtitulo') is-invalid @enderror" value="{{ old('subtitulo', $fraseInicio->subtitulo ?? 'Associação de Aeromodelismo e Automodelismo') }}" required>
                @error('subtitulo')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="localizacao" class="form-label"><strong>Localização:</strong></label>
                <input type="text" name="localizacao" id="localizacao" class="form-control @error('localizacao') is-invalid @enderror" value="{{ old('localizacao', $fraseInicio->localizacao ?? 'Paranavaí - PR') }}" required>
                @error('localizacao')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="frase_inicio" class="form-label"><strong>Frase de Boas-vindas:</strong></label>
                <textarea name="frase_inicio" id="frase_inicio" class="form-control @error('frase_inicio') is-invalid @enderror" rows="3" required>{{ old('frase_inicio', $fraseInicio->frase ?? '') }}</textarea>
                @error('frase_inicio')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="descricao" class="form-label"><strong>Descrição da Associação:</strong></label>
                <textarea name="descricao" id="descricao" class="form-control @error('descricao') is-invalid @enderror" rows="4" required>{{ old('descricao', $fraseInicio->descricao ?? 'Uma associação dedicada aos amantes do aeromodelismo e automodelismo') }}</textarea>
                @error('descricao')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="como_associar_se" class="form-label"><strong>Como Associar-se:</strong></label>
                <textarea name="como_associar_se" id="como_associar_se" class="form-control @error('como_associar_se') is-invalid @enderror" rows="4" placeholder="Descreva o processo para se tornar um membro da associação...">{{ old('como_associar_se', $fraseInicio->como_associar_se ?? '') }}</textarea>
                @error('como_associar_se')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-floppy-disk"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection