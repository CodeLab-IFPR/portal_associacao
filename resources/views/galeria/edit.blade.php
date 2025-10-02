@extends('layouts.admin')

@section('title')
    Editar Mídia
@endsection

@section('content')
    <div class="container py-4">
        <h4 class="fs-1 fw-bold mb-6 text-black text-center">Editar Mídia</h4>

        <div class="card shadow-sm">
            <div class="card-body">
                <form id="edit-media-form" action="{{ route('galeria.update', $galeria->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título do Evento</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" value="{{ old('titulo', $galeria->titulo) }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="data_inicio_evento" class="form-label">Data de Início do Evento</label>
                            <input type="date" class="form-control" id="data_inicio_evento" name="data_inicio_evento" value="{{ old('data_inicio_evento', optional($galeria->data_inicio_evento)->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="data_fim_evento" class="form-label">Data de Fim do Evento</label>
                            <input type="date" class="form-control" id="data_fim_evento" name="data_fim_evento" value="{{ old('data_fim_evento', optional($galeria->data_fim_evento)->format('Y-m-d')) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3">{{ old('descricao', $galeria->descricao) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="tipo_midia" class="form-label">Tipo da Mídia Principal</label>
                        <select class="form-select" id="tipo_midia" name="tipo_midia" required>
                            <option value="imagem" {{ old('tipo_midia', $galeria->tipo) == 'imagem' ? 'selected' : '' }}>Imagem</option>
                            <option value="video" {{ old('tipo_midia', $galeria->tipo) == 'video' ? 'selected' : '' }}>Vídeo do YouTube</option>
                        </select>
                    </div>

                    <div id="campo-imagem" class="mb-3">
                        <label for="imagem_principal" class="form-label">Imagem Principal (Capa)</label>
                        <input type="file" class="form-control" id="imagem_principal" name="imagem_principal" accept="image/jpeg, image/png, image/webp">
                        @if($galeria->tipo == 'imagem')
                            <div class="mt-2">
                                <small>Imagem atual:</small>
                                <img src="{{ asset($galeria->caminho) }}" alt="Imagem atual" class="img-thumbnail mt-1" style="max-height: 150px;">
                            </div>
                        @endif
                    </div>

                    <div id="campo-video" class="mb-3">
                        <label for="link_youtube" class="form-label">URL do Vídeo do YouTube</label>
                        <input type="text" class="form-control" id="link_youtube" name="link_youtube" placeholder="https://www.youtube.com/watch?v=..." value="{{ old('link_youtube', $galeria->tipo == 'video' ? $galeria->caminho : '') }}">
                    </div>

                    <div class="mb-3">
                        <label for="arquivos" class="form-label">Adicionar mais Imagens à Galeria</label>
                        <input class="form-control" type="file" id="arquivos" name="arquivos[]" multiple accept="image/jpeg, image/png, image/webp">
                    </div>

                    <div id="galeria-midias-existentes" class="mb-4">
                        <label class="form-label">Imagens da Galeria Atuais</label>
                        @if($galeria->galerias->count() > 0)
                            <div class="row row-cols-2 row-cols-md-4 g-3">
                                @foreach($galeria->galerias as $midia)
                                    <div class="col" id="media-container-{{ $midia->id }}">
                                        <div class="card h-100">
                                            <img src="{{ asset($midia->caminho) }}" class="card-img-top" alt="Imagem da galeria" style="object-fit: cover; height: 150px;">
                                            <div class="card-footer text-center">
                                                <div class="card-footer text-center">
                                                    <button type="button"
                                                            class="btn btn-danger btn-sm delete-media-btn"
                                                            data-id="{{ $midia->id }}"
                                                            data-caminho="{{ $midia->caminho }}"
                                                            data-url="{{ route('galeria.media.destroy', $midia->id) }}">
                                                        Excluir
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">Nenhuma imagem adicional na galeria.</p>
                        @endif
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('galeria.indexAdmin') }}" class="btn btn-secondary me-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('edit-media-form');
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
                    campoVideo.style.display = 'none';
                    inputVideo.required = false;
                    inputVideo.value = '';
                } else {
                    campoImagem.style.display = 'none';
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
                            const maxSizeInBytes = 5 * 1024 * 1024;
                            if (file.size > maxSizeInBytes) {
                                alert(`O arquivo "${file.name}" é muito pesado! O tamanho máximo permitido é 5MB.`);
                                event.preventDefault();
                                isValid = false;
                            }
                            const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
                            if (!allowedTypes.includes(file.type)) {
                                alert(`O tipo de arquivo de "${file.name}" não é permitido. Use apenas JPG, PNG ou WebP.`);
                                event.preventDefault();
                                isValid = false;
                            }
                        }
                    }
                });
            });

            const deleteButtons = document.querySelectorAll('.delete-media-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const mediaId = this.dataset.id;
                    const mediaCaminho = this.dataset.caminho;
                    const deleteUrl = this.dataset.url;

                    const confirmationMessage = `Tem certeza que deseja excluir esta imagem?\n\nArquivo: ${mediaCaminho}`;

                    if (!confirm(confirmationMessage)) return;

                    fetch(deleteUrl, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => {
                            if (!response.ok) throw new Error('A resposta do servidor não foi bem-sucedida.');
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                document.getElementById(`media-container-${mediaId}`).remove();
                                alert(data.message);
                            } else {
                                alert('Erro ao excluir: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Fetch Error:', error);
                            alert('Ocorreu um erro de comunicação ao tentar excluir o arquivo.');
                        });
                });
            });
        });
    </script>
@endsection
