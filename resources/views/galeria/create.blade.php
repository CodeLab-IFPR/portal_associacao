@extends('layouts.admin')

@section('title')
    Nova Mídia
@endsection

@section('content')
    <div class="container py-4">
        <h4 class="fs-1 fw-bold mb-6 text-black text-center">Cadastrar Nova Mídia</h4>

        <div class="card shadow-sm">
            <div class="card-body">
                {{-- Adicionado um ID ao formulário para facilitar a manipulação no JS --}}
                <form id="media-form" action="{{ route('galeria.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título do Evento</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" value="{{ old('titulo') }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="data_inicio_evento" class="form-label">Data de Início do Evento</label>
                            <input type="date" class="form-control" id="data_inicio_evento" name="data_inicio_evento" value="{{ old('data_inicio_evento') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="data_fim_evento" class="form-label">Data de Fim do Evento</label>
                            <input type="date" class="form-control" id="data_fim_evento" name="data_fim_evento" value="{{ old('data_fim_evento') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3">{{ old('descricao') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="tipo_midia" class="form-label">Tipo da Mídia Principal</label>
                        <select class="form-select" id="tipo_midia" name="tipo_midia" required>
                            <option value="imagem" {{ old('tipo_midia') == 'imagem' ? 'selected' : '' }}>Imagem</option>
                            <option value="video" {{ old('tipo_midia') == 'video' ? 'selected' : '' }}>Vídeo do YouTube</option>
                        </select>
                    </div>

                    <div id="campo-imagem" class="mb-3" style="{{ old('tipo_midia') == 'video' ? 'display: none;' : 'display: block;' }}">
                        <label for="imagem_principal" class="form-label">Imagem Principal (Capa)</label>
                        <input type="file" class="form-control" id="imagem_principal" name="imagem_principal" accept="image/jpeg, image/png, image/webp" {{ old('tipo_midia') == 'imagem' ? 'required' : '' }}>
                    </div>

                    <div id="campo-video" class="mb-3" style="{{ old('tipo_midia') == 'imagem' ? 'display: none;' : 'display: block;' }}">
                        <label for="link_youtube" class="form-label">URL do Vídeo do YouTube</label>
                        <input type="text" class="form-control" id="link_youtube" name="link_youtube" placeholder="https://www.youtube.com/watch?v=..." value="{{ old('link_youtube') }}" {{ old('tipo_midia') == 'video' ? 'required' : '' }}>
                    </div>

                    <div class="mb-3">
                        <label for="arquivos" class="form-label">Imagens da Galeria (selecione múltiplas)</label>
                        <input class="form-control" type="file" id="arquivos" name="arquivos[]" multiple accept="image/jpeg, image/png, image/webp">
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('galeria.indexAdmin') }}" class="btn btn-secondary me-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
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

                const hoje = new Date().toISOString().split('T')[0];
                const dataInicio = dataInicioInput.value;
                const dataFim = dataFimInput.value;

                if (dataInicio && dataInicio < hoje) {
                    alert('A data de início do evento não pode ser anterior à data de hoje.');
                    event.preventDefault();
                    isValid = false;
                }

                if (dataInicio && dataFim && dataFim < dataInicio) {
                    alert('A data de fim do evento deve ser posterior à data de início.');
                    event.preventDefault();
                    isValid = false;
                }

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
