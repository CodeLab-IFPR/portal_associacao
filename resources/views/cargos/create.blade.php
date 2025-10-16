@extends('layouts.admin')

@section('title', 'Criar Novo Cargo')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Criar Novo Cargo</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cargos.index') }}">Cargos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Criar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12" style="max-width: 600px;">
                
                <!-- Ações -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </h6>
                    </div>
                    <div class="card-body py-2">
                        <a href="{{ route('cargos.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar à Lista
                        </a>
                    </div>
                </div>

                <!-- Formulário -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-plus"></i> Dados do Novo Cargo
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('cargos.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="descricao" class="form-label fw-bold">
                                    <i class="fas fa-briefcase me-1"></i> Descrição do Cargo *
                                </label>
                                <input type="text" 
                                       class="form-control @error('descricao') is-invalid @enderror" 
                                       id="descricao" 
                                       name="descricao" 
                                       value="{{ old('descricao') }}" 
                                       placeholder="Ex: Gerente de Projetos, Analista de Sistemas..."
                                       maxlength="255"
                                       required>
                                
                                @error('descricao')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                                
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Digite uma descrição única para o cargo (máximo 255 caracteres).
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="bg-light p-3 rounded mb-4">
                                        <h6 class="text-primary mb-2">
                                            <i class="fas fa-lightbulb me-1"></i> Dicas:
                                        </h6>
                                        <ul class="mb-0 small">
                                            <li>Use nomes claros e descritivos para os cargos</li>
                                            <li>Evite abreviações que possam confundir</li>
                                            <li>Cada cargo deve ter uma descrição única</li>
                                            <li>Considere a hierarquia organizacional</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('cargos.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Salvar Cargo
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Foco automático no campo descrição
    $('#descricao').focus();
    
    // Contador de caracteres
    $('#descricao').on('input', function() {
        var maxLength = 255;
        var currentLength = $(this).val().length;
        var remaining = maxLength - currentLength;
        
        if (remaining < 50) {
            $(this).next('.form-text').html(
                '<i class="fas fa-info-circle me-1"></i>' +
                'Restam ' + remaining + ' caracteres'
            );
        }
    });
});
</script>
@endpush