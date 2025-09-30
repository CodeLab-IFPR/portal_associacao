@extends('layouts.admin')

@section('title')
Adicionar Documento
@endsection

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

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Enviar Novo Documento</h3>
                    </div>
                    <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="arquivo" class="form-label">Arquivo PDF *</label>
                                        <input type="file" 
                                               class="form-control @error('arquivo') is-invalid @enderror" 
                                               id="arquivo" 
                                               name="arquivo" 
                                               accept=".pdf"
                                               required>
                                        <div class="form-text">
                                            Apenas arquivos PDF são permitidos. Tamanho máximo: 10MB.
                                        </div>
                                        @error('arquivo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tipo_documento" class="form-label">Tipo do Documento *</label>
                                        <select class="form-select @error('tipo_documento') is-invalid @enderror" 
                                                id="tipo_documento" 
                                                name="tipo_documento" 
                                                required>
                                            <option value="">Selecione o tipo</option>
                                            <option value="RG" {{ old('tipo_documento') === 'RG' ? 'selected' : '' }}>RG</option>
                                            <option value="CPF" {{ old('tipo_documento') === 'CPF' ? 'selected' : '' }}>CPF</option>
                                            <option value="Comprovante de Residência" {{ old('tipo_documento') === 'Comprovante de Residência' ? 'selected' : '' }}>Comprovante de Residência</option>
                                            <option value="Diploma" {{ old('tipo_documento') === 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                            <option value="Certificado" {{ old('tipo_documento') === 'Certificado' ? 'selected' : '' }}>Certificado</option>
                                            <option value="Currículo" {{ old('tipo_documento') === 'Currículo' ? 'selected' : '' }}>Currículo</option>
                                            <option value="Termo de Compromisso" {{ old('tipo_documento') === 'Termo de Compromisso' ? 'selected' : '' }}>Termo de Compromisso</option>
                                            <option value="Declaração" {{ old('tipo_documento') === 'Declaração' ? 'selected' : '' }}>Declaração</option>
                                            <option value="Outros" {{ old('tipo_documento') === 'Outros' ? 'selected' : '' }}>Outros</option>
                                        </select>
                                        @error('tipo_documento')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                          id="descricao" 
                                          name="descricao" 
                                          rows="3"
                                          placeholder="Descreva brevemente o conteúdo do documento...">{{ old('descricao') }}</textarea>
                                <div class="form-text">
                                    Máximo 1000 caracteres.
                                </div>
                                @error('descricao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-info top-50 start-50">
                                <i class="bi bi-info-circle"></i>
                                <strong>Importante:</strong>
                                <button type="button" id="close" class="btn-close float-end transparent"></button>
                                <ul class="mb-0 mt-2">
                                    <li>O documento será enviado para análise do administrador.</li>
                                    <li>Você receberá uma notificação quando o documento for aprovado ou rejeitado.</li>
                                    <li>Após aprovado, o documento não poderá ser excluído.</li>
                                    <li>Certifique-se de que o arquivo está correto antes de enviar.</li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload"></i> Enviar Documento
                            </button>
                            <a href="{{ route('documentos.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Voltar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Mostrar nome do arquivo selecionado
    document.getElementById('arquivo').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        if (fileName) {
            const fileText = this.nextElementSibling;
            fileText.textContent = `Arquivo selecionado: ${fileName}`;
        }
    });

    document.getElementById('close').addEventListener('click', function() {
        this.parentElement.style.display = 'none';
    });


</script>
@endsection
