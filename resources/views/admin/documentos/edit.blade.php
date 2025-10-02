@extends('layouts.admin')

@section('title', 'Editar Documento - ' . str_replace('_', ' ', preg_replace('/\.pdf$/i', '', $documento->nome_original)))

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Editar Documento</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('documentos.index') }}">Documentos</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>Editar Documento
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('documentos.update', $documento) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-primary fw-bold mb-3">Dados do Documento</h6>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="tipo_documento" class="form-label">Tipo do Documento *</label>
                        <select class="form-select @error('tipo_documento') is-invalid @enderror" 
                                id="tipo_documento" 
                                name="tipo_documento" 
                                required>
                            <option value="{{ $documento->tipo_documento }}">Manter como {{ $documento->tipo_documento }}</option>
                            @foreach(['RG', 'CPF', 'Comprovante de Residência', 'Diploma', 'Certificado', 'Currículo', 'Termo de Compromisso', 'Declaração', 'Outros'] as $tipo)
                                @if($tipo !== $documento->tipo_documento)
                                    <option value="{{ $tipo }}" {{ old('tipo_documento') === $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('tipo_documento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição do Documento</label>
                                <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                          id="descricao" 
                                          name="descricao" 
                                          rows="4" 
                                          placeholder="Descreva brevemente o conteúdo do documento...">{{ old('descricao', $documento->descricao) }}</textarea>
                                <div class="form-text">
                                    Máximo 1000 caracteres.
                                </div>
                                @error('descricao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    @if($documento->caminho_arquivo)
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Arquivo Atual</label>
                                <div class="d-flex align-items-center p-3 bg-light border rounded">
                                    <i class="fas fa-file-pdf fa-2x text-danger me-3"></i>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $documento->nome_original }}</h6>
                                        <small class="text-muted">Arquivo PDF atual</small>
                                    </div>
                                    <a href="{{ route('documentos.download', $documento) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-download me-1"></i>Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="arquivo" class="form-label">
                                    {{ $documento->caminho_arquivo ? 'Substituir Arquivo PDF' : 'Arquivo PDF *' }}
                                </label>
                                <input type="file" 
                                       class="form-control @error('arquivo') is-invalid @enderror" 
                                       id="arquivo" 
                                       name="arquivo" 
                                       accept=".pdf"
                                       {{ !$documento->caminho_arquivo ? 'required' : '' }}>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    {{ $documento->caminho_arquivo ? 'Deixe em branco para manter o arquivo atual. ' : '' }}
                                    Somente arquivos PDF. Tamanho máximo: 10MB
                                </div>
                                @error('arquivo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('documentos.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Atualizar Documento
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview do arquivo selecionado
    const fileInput = document.getElementById('arquivo');
    
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const fileName = this.files[0].name;
            const fileSize = (this.files[0].size / 1024 / 1024).toFixed(2);
            
            // Criar ou atualizar o preview
            let preview = document.getElementById('file-preview');
            if (!preview) {
                preview = document.createElement('div');
                preview.id = 'file-preview';
                preview.className = 'mt-2 p-2 bg-light border rounded';
                fileInput.parentNode.appendChild(preview);
            }
            
            preview.innerHTML = `
                <i class="fas fa-file-pdf text-danger me-2"></i>
                <strong>Novo arquivo:</strong> ${fileName} (${fileSize} MB)
            `;
        }
    });
});
</script>
@endsection
