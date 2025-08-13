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
                <label for="frase_inicio" class="form-label"><strong>Frase Tela Inicial:</strong></label>
                <textarea name="frase_inicio" id="frase_inicio" class="form-control @error('frase_inicio') is-invalid @enderror" rows="6" required>{{ old('frase_inicio', $fraseInicio->frase ?? '') }}</textarea>
                @error('frase_inicio')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="frase_sobre" class="form-label"><strong>Frase Sobre Nós:</strong></label>
                <textarea name="frase_sobre" id="frase_sobre" class="form-control @error('frase_sobre') is-invalid @enderror" rows="6" required>{{ old('frase_sobre', $fraseSobre->frase ?? '') }}</textarea>
                @error('frase_sobre')
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