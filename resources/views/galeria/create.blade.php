@extends('layouts.admin')

@section('title')
    Nova Mídia
@endsection

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Nova Mídia</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('galeria.indexAdmin') }}">Galeria</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nova Mídia</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container pt-2 d-flex justify-content-center">
    <div class="card-body p-4 p-lg-5 rounded-4 shadow-sm bg-white" style="max-width: 1000px; width: 100%;">
        <header class="text-center mb-4">
            <h2 class="fs-4 fw-medium mb-3">{{ __('Cadastrar Nova Mídia') }}</h2>
            <p class="text-muted">{{ __("Adicione uma nova mídia à galeria com imagens ou vídeos do YouTube.") }}</p>
        </header>

        <form id="media-form" action="{{ route('galeria.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Informações do Evento -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Informações do Evento</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="titulo" class="form-label"><strong>Título do Evento:</strong></label>
                        <input type="text" class="form-control @error('titulo') is-invalid @enderror" id="titulo" name="titulo" value="{{ old('titulo') }}" placeholder="Digite o título do evento..." required>
                        @error('titulo')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="data_inicio_evento" class="form-label"><strong>Data de Início do Evento:</strong></label>
                                <input type="date" class="form-control @error('data_inicio_evento') is-invalid @enderror" id="data_inicio_evento" name="data_inicio_evento" value="{{ old('data_inicio_evento') }}" required>
                                @error('data_inicio_evento')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="data_fim_evento" class="form-label"><strong>Data de Fim do Evento:</strong></label>
                                <input type="date" class="form-control @error('data_fim_evento') is-invalid @enderror" id="data_fim_evento" name="data_fim_evento" value="{{ old('data_fim_evento') }}">
                                <div class="form-text">Opcional - deixe em branco se for um evento de um dia.</div>
                                @error('data_fim_evento')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label"><strong>Descrição:</strong></label>
                        <textarea class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao" rows="3" placeholder="Descreva o evento...">{{ old('descricao') }}</textarea>
                        @error('descricao')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Mídia Principal -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-play-circle"></i> Mídia Principal</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="tipo_midia" class="form-label"><strong>Tipo da Mídia Principal:</strong></label>
                        <select class="form-select @error('tipo_midia') is-invalid @enderror" id="tipo_midia" name="tipo_midia" required>
                            <option value="imagem" {{ old('tipo_midia') == 'imagem' ? 'selected' : '' }}>Imagem</option>
                            <option value="video" {{ old('tipo_midia') == 'video' ? 'selected' : '' }}>Vídeo do YouTube</option>
                        </select>
                        @error('tipo_midia')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="campo-imagem" class="mb-3" style="{{ old('tipo_midia') == 'video' ? 'display: none;' : 'display: block;' }}">
                        <label for="imagem_principal" class="form-label"><strong>Imagem Principal (Capa):</strong></label>
                        <input type="file" class="form-control @error('imagem_principal') is-invalid @enderror" id="imagem_principal" name="imagem_principal" accept="image/jpeg, image/png, image/webp" {{ old('tipo_midia') == 'imagem' ? 'required' : '' }}>
                        <div class="form-text">Formatos aceitos: JPG, PNG, WebP. Tamanho máximo: 5MB.</div>
                        @error('imagem_principal')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="campo-video" class="mb-3" style="{{ old('tipo_midia') == 'imagem' ? 'display: none;' : 'display: block;' }}">
                        <label for="link_youtube" class="form-label"><strong>URL do Vídeo do YouTube:</strong></label>
                        <input type="url" class="form-control @error('link_youtube') is-invalid @enderror" id="link_youtube" name="link_youtube" placeholder="https://www.youtube.com/watch?v=..." value="{{ old('link_youtube') }}" {{ old('tipo_midia') == 'video' ? 'required' : '' }}>
                        <div class="form-text">Cole o link completo do vídeo do YouTube.</div>
                        @error('link_youtube')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Galeria de Imagens -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-images"></i> Galeria de Imagens</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="arquivos" class="form-label"><strong>Imagens da Galeria (selecione múltiplas):</strong></label>
                        <input class="form-control @error('arquivos') is-invalid @enderror" type="file" id="arquivos" name="arquivos[]" multiple accept="image/jpeg, image/png, image/webp">
                        <div class="form-text">Opcional - Adicione imagens extras para a galeria. Formatos aceitos: JPG, PNG, WebP. Tamanho máximo por arquivo: 5MB.</div>
                        @error('arquivos')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <a href="{{ route('galeria.indexAdmin') }}" class="btn btn-secondary me-md-2">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Salvar Mídia
                </button>
            </div>
        </form>
    </div>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('media-form');
            const tipoSelect = document.getElementById('tipo_midia');
            const campoImagem = document.getElementById('campo-imagem');
            const campoVideo = document.getElementById('campo-video');
            const inputImagem = document.getElementById('imagem_principal');
            const inputVideo = document.getElementById('link_youtube');
            const arquivosGaleria = document.getElementById('arquivos');
            const dataInicioInput = document.getElementById('data_inicio_evento');
            const dataFimInput = document.getElementById('data_fim_evento');

            function toggleCampos() {
                const tipo = tipoSelect.value;
                if (tipo === 'imagem') {
                    campoImagem.style.display = 'block';
                    inputImagem.required = true;
                    campoVideo.style.display = 'none';
                    inputVideo.required = false;
                    inputVideo.value = '';
                } else {
                    campoImagem.style.display = 'none';
                    inputImagem.required = false;
                    inputImagem.value = '';
                    campoVideo.style.display = 'block';
                    inputVideo.required = true;
                }
            }

            tipoSelect.addEventListener('change', toggleCampos);
            toggleCampos();

            form.addEventListener('submit', function(event) {
                let isValid = true;

                const dataInicio = dataInicioInput.value;
                const dataFim = dataFimInput.value;

                // Validar apenas se a data de fim é posterior à data de início
                if (dataInicio && dataFim && dataFim < dataInicio) {
                    alert('A data de fim do evento deve ser posterior à data de início.');
                    event.preventDefault();
                    isValid = false;
                }

                // Validar se a data não é muito no futuro (prevenção de erro)
                if (dataFim && new Date(dataFim).getFullYear() >= 2099) {
                    alert('A data de fim do evento não pode ser em 2099 ou posterior.');
                    event.preventDefault();
                    isValid = false;
                }

                if (!isValid) return;

                const arquivosParaValidar = [inputImagem, arquivosGaleria];
                arquivosParaValidar.forEach(inputField => {
                    if (inputField.files.length > 0) {
                        for (const file of inputField.files) {
                            const maxSizeInBytes = 5 * 1024 * 1024; // 5MB
                            if (file.size > maxSizeInBytes) {
                                alert(`O arquivo "${file.name}" é muito pesado! O tamanho máximo permitido é 5MB.`);
                                event.preventDefault();
                                isValid = false;
                            }

                            const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
                            if (!allowedTypes.includes(file.type)) {
                                alert(`O tipo de arquivo "${file.name}" não é permitido. Use apenas JPG, PNG ou WebP.`);
                                event.preventDefault();
                                isValid = false;
                            }
                        }
                    }
                });
            });
        });
    </script>
@endsection
