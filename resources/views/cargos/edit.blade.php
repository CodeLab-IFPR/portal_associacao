@extends('layouts.admin')

@section('title', 'Editar Cargo')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Editar Cargo</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cargos.index') }}">Cargos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar</li>
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
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-edit"></i> Editar Dados do Cargo
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('cargos.update', $cargo) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <!-- Informações do Cargo Atual -->
                            <div class="alert alert-info mb-4">
                                <h6 class="alert-heading">
                                    <i class="fas fa-info-circle me-1"></i> Cargo Atual:
                                </h6>
                                <p class="mb-1"><strong>ID:</strong> #{{ $cargo->id }}</p>
                                <p class="mb-1"><strong>Descrição:</strong> {{ $cargo->descricao }}</p>
                                <p class="mb-0"><strong>Criado em:</strong> {{ $cargo->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <label for="descricao" class="form-label fw-bold">
                                    <i class="fas fa-briefcase me-1"></i> Nova Descrição do Cargo *
                                </label>
                                <input type="text" 
                                       class="form-control @error('descricao') is-invalid @enderror" 
                                       id="descricao" 
                                       name="descricao" 
                                       value="{{ old('descricao', $cargo->descricao) }}" 
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
                                        <h6 class="text-warning mb-2">
                                            <i class="fas fa-exclamation-triangle me-1"></i> Atenção:
                                        </h6>
                                        <ul class="mb-0 small">
                                            <li>Certifique-se de que a nova descrição é única no sistema</li>
                                            <li>Verifique se não há funcionários vinculados a este cargo</li>
                                            <li>A alteração será aplicada imediatamente após salvar</li>
                                            <li>Mantenha consistência com a hierarquia organizacional</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('cargos.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Salvar Alterações
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
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
    
    .alert-info {
        border-color: #bee5eb;
        background-color: #d1ecf1;
        color: #0c5460;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Selecionar todo o texto no campo ao focar
    $('#descricao').focus().select();
    
    // Contador de caracteres
    $('#descricao').on('input', function() {
        var maxLength = 255;
        var currentLength = $(this).val().length;
        var remaining = maxLength - currentLength;
        
        if (remaining < 50) {
            $(this).siblings('.form-text').html(
                '<i class="fas fa-info-circle me-1"></i>' +
                'Restam ' + remaining + ' caracteres'
            );
        }
    });
});
</script>
@endpush