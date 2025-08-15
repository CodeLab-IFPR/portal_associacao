@extends('layouts.admin')

@section('title', 'Editar ATA - ' . $ata->titulo)

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Editar ATA</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.atas.index') }}">ATAs</a></li>
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
                    <i class="fas fa-edit me-2"></i>Editar ATA
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.atas.update', $ata) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-primary fw-bold mb-3">Dados da ATA</h6>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título da ATA *</label>
                                <input type="text" 
                                       class="form-control @error('titulo') is-invalid @enderror" 
                                       id="titulo" 
                                       name="titulo" 
                                       value="{{ old('titulo', $ata->titulo) }}" 
                                       placeholder="Digite o título da ATA"
                                       required>
                                @error('titulo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição da ATA *</label>
                                <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                          id="descricao" 
                                          name="descricao" 
                                          rows="4" 
                                          placeholder="Descreva o conteúdo da ATA"
                                          required>{{ old('descricao', $ata->descricao) }}</textarea>
                                @error('descricao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    @if($ata->arquivo)
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Arquivo Atual</label>
                                <div class="d-flex align-items-center p-3 bg-light border rounded">
                                    <i class="fas fa-file-pdf fa-2x text-danger me-3"></i>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $ata->arquivo_original }}</h6>
                                        <small class="text-muted">Arquivo PDF atual</small>
                                    </div>
                                    <a href="{{ route('admin.atas.download', $ata) }}" 
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
                                    {{ $ata->arquivo ? 'Substituir Arquivo PDF' : 'Arquivo PDF *' }}
                                </label>
                                <input type="file" 
                                       class="form-control @error('arquivo') is-invalid @enderror" 
                                       id="arquivo" 
                                       name="arquivo" 
                                       accept=".pdf"
                                       {{ !$ata->arquivo ? 'required' : '' }}>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    {{ $ata->arquivo ? 'Deixe em branco para manter o arquivo atual. ' : '' }}
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
                                <a href="{{ route('admin.atas.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Atualizar ATA
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
