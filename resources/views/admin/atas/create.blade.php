@extends('layouts.admin')

@section('title', 'Cadastrar ATA')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Cadastrar ATA</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.atas.index') }}">ATAs</a></li>
                    <li class="breadcrumb-item active">Cadastrar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container pt-2 d-flex justify-content-center">
    <div class="card-body p-4 p-lg-5 rounded-4 shadow-sm bg-white" style="max-width: 1000px; width: 100%;">
        <header class="text-center mb-4">
            <h2 class="fs-4 fw-medium mb-3">{{ __('Cadastrar Nova ATA') }}</h2>
            <p class="text-muted">{{ __("Adicione uma nova Ata de Reunião ou Assembleia ao sistema.") }}</p>
        </header>

        <form action="{{ route('admin.atas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Informações da ATA -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-file-alt"></i> Informações da ATA</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="titulo" class="form-label"><strong>Título da ATA:</strong></label>
                                <input type="text" 
                                       class="form-control @error('titulo') is-invalid @enderror" 
                                       id="titulo" 
                                       name="titulo" 
                                       value="{{ old('titulo') }}" 
                                       placeholder="Digite o título da ATA..."
                                       required>
                                @error('titulo')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="descricao" class="form-label"><strong>Descrição da ATA:</strong></label>
                                <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                          id="descricao" 
                                          name="descricao" 
                                          rows="4" 
                                          placeholder="Descreva o conteúdo da ATA, principais pontos discutidos..."
                                          required>{{ old('descricao') }}</textarea>
                                @error('descricao')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Arquivo PDF -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-file-pdf"></i> Arquivo PDF</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="arquivo" class="form-label"><strong>Arquivo PDF:</strong></label>
                                <input type="file" 
                                       class="form-control @error('arquivo') is-invalid @enderror" 
                                       id="arquivo" 
                                       name="arquivo" 
                                       accept=".pdf"
                                       required>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Somente arquivos PDF são aceitos. Tamanho máximo: 10MB.
                                </div>
                                @error('arquivo')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <a href="{{ route('admin.atas.index') }}" class="btn btn-secondary me-md-2">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Salvar ATA
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview do arquivo selecionado
    const fileInput = document.getElementById('arquivo');
    const fileLabel = document.querySelector('label[for="arquivo"]');
    
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
                <strong>${fileName}</strong> (${fileSize} MB)
            `;
        }
    });
});
</script>
@endsection
