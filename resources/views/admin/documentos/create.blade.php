@extends('layouts.admin')

@section('title', 'Adicionar Documento')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Adicionar Documento</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('documentos.index') }}">Documentos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Adicionar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container pt-2 d-flex justify-content-center">
    <div class="card-body p-4 p-lg-5 rounded-4 shadow-sm bg-white" style="max-width: 1000px; width: 100%;">
        <header class="text-center mb-4">
            <h2 class="fs-4 fw-medium mb-3">{{ __('Enviar Novo Documento') }}</h2>
            <p class="text-muted">{{ __("Faça upload de documentos importantes para análise e aprovação pela administração.") }}</p>
        </header>

        <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Informações do Documento -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-file-upload"></i> Informações do Documento</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="arquivo" class="form-label"><strong>Arquivo PDF:</strong></label>
                                <input type="file" 
                                       class="form-control @error('arquivo') is-invalid @enderror" 
                                       id="arquivo" 
                                       name="arquivo" 
                                       accept=".pdf"
                                       required>
                                <div class="form-text" id="file-info">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Apenas arquivos PDF. Tamanho máximo: 10MB.
                                </div>
                                @error('arquivo')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo_documento" class="form-label"><strong>Tipo do Documento:</strong></label>
                                <select class="form-select @error('tipo_documento') is-invalid @enderror" 
                                        id="tipo_documento" 
                                        name="tipo_documento" 
                                        required>
                                    <option value="">Selecione o tipo do documento...</option>
                                    <option value="RG" {{ old('tipo_documento') === 'RG' ? 'selected' : '' }}>RG - Registro Geral</option>
                                    <option value="CPF" {{ old('tipo_documento') === 'CPF' ? 'selected' : '' }}>CPF - Cadastro de Pessoa Física</option>
                                    <option value="Comprovante de Residência" {{ old('tipo_documento') === 'Comprovante de Residência' ? 'selected' : '' }}>Comprovante de Residência</option>
                                    <option value="Diploma" {{ old('tipo_documento') === 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                    <option value="Certificado" {{ old('tipo_documento') === 'Certificado' ? 'selected' : '' }}>Certificado</option>
                                    <option value="Currículo" {{ old('tipo_documento') === 'Currículo' ? 'selected' : '' }}>Currículo</option>
                                    <option value="Termo de Compromisso" {{ old('tipo_documento') === 'Termo de Compromisso' ? 'selected' : '' }}>Termo de Compromisso</option>
                                    <option value="Declaração" {{ old('tipo_documento') === 'Declaração' ? 'selected' : '' }}>Declaração</option>
                                    <option value="Outros" {{ old('tipo_documento') === 'Outros' ? 'selected' : '' }}>Outros</option>
                                </select>
                                @error('tipo_documento')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Descrição -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Descrição</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="descricao" class="form-label"><strong>Descrição do Documento:</strong></label>
                                <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                          id="descricao" 
                                          name="descricao" 
                                          rows="4"
                                          placeholder="Descreva brevemente o conteúdo do documento, sua finalidade ou informações relevantes...">{{ old('descricao') }}</textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Máximo 1000 caracteres. Esta descrição ajuda na organização e busca dos documentos.
                                </div>
                                @error('descricao')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aviso Importante (apenas para não-administradores) -->
            @if(!auth()->user()->hasRole('Admin'))
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle"></i> Informações Importantes
                        <button type="button" id="close" class="btn-close float-end" aria-label="Fechar"></button>
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li class="mb-2"><strong>Análise:</strong> O documento será enviado para análise do administrador.</li>
                        <li class="mb-2"><strong>Notificação:</strong> Você receberá uma notificação quando o documento for aprovado ou rejeitado.</li>
                        <li class="mb-2"><strong>Aprovação:</strong> Após aprovado, o documento não poderá ser excluído.</li>
                        <li class="mb-0"><strong>Verificação:</strong> Certifique-se de que o arquivo está correto antes de enviar.</li>
                    </ul>
                </div>
            </div>
            @endif

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <a href="{{ route('documentos.index') }}" class="btn btn-secondary me-md-2">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-upload"></i> Enviar Documento
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mostrar nome do arquivo selecionado
    const arquivoInput = document.getElementById('arquivo');
    const fileInfo = document.getElementById('file-info');
    
    arquivoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const fileName = file.name;
            const fileSize = (file.size / 1024 / 1024).toFixed(2); // Size in MB
            
            fileInfo.innerHTML = `
                <i class="fas fa-check-circle text-success me-1"></i>
                <strong>Arquivo selecionado:</strong> ${fileName} (${fileSize} MB)
            `;
        } else {
            fileInfo.innerHTML = `
                <i class="fas fa-info-circle me-1"></i>
                Apenas arquivos PDF. Tamanho máximo: 10MB.
            `;
        }
    });

    // Fechar aviso importante
    const closeButton = document.getElementById('close');
    if (closeButton) {
        closeButton.addEventListener('click', function() {
            this.closest('.card').style.display = 'none';
        });
    }
});
</script>
@endsection
