@extends('layouts.admin')

@section('title')
    Nova Mídia
@endsection

@section('content')
    <div class="container py-4">
        <h4 class="fs-1 fw-bold mb-6 text-black text-center">Cadastrar Nova Mídia</h4>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('galeria.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título do Evento</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" value="{{ old('titulo') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="data_evento" class="form-label">Data do Evento</label>
                        <input type="date" class="form-control" id="data_evento" name="data_evento" value="{{ old('data_evento') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3">{{ old('descricao') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo da Mídia Principal</label>
                        <select class="form-select" id="tipo" name="tipo" required>
                            <option value="imagem" selected>Imagem</option>
                            <option value="video">Vídeo do YouTube</option>
                        </select>
                    </div>

                    <div id="campo-imagem" class="mb-3">
                        <label for="imagem_principal" class="form-label">Imagem Principal (Capa)</label>
                        <input type="file" class="form-control" id="imagem_principal" name="imagem_principal" accept="image/*">
                    </div>

                    <div id="campo-video" class="mb-3" style="display: none;">
                        <label for="youtube_url" class="form-label">URL do Vídeo do YouTube</label>
                        <input type="text" class="form-control" id="youtube_url" name="youtube_url" placeholder="https://www.youtube.com/watch?v=...">
                    </div>

                    <div class="mb-3">
                        <label for="arquivos_galeria" class="form-label">Imagens da Galeria (selecione múltiplas)</label>
                        <input class="form-control" type="file" id="arquivos_galeria" name="arquivos_galeria[]" multiple accept="image/*">
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('galeria.indexPublic') }}" class="btn btn-secondary me-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('tipo').addEventListener('change', function () {
            const tipo = this.value;
            const campoImagem = document.getElementById('campo-imagem');
            const campoVideo = document.getElementById('campo-video');
            const inputImagem = document.getElementById('imagem_principal');
            const inputVideo = document.getElementById('youtube_url');

            if (tipo === 'imagem') {
                campoImagem.style.display = 'block';
                campoVideo.style.display = 'none';
                inputImagem.required = true;
                inputVideo.required = false;
            } else {
                campoImagem.style.display = 'none';
                campoVideo.style.display = 'block';
                inputImagem.required = false;
                inputVideo.required = true;
            }
        });

        // Dispara o evento 'change' na carga da página para definir o estado inicial correto
        document.getElementById('tipo').dispatchEvent(new Event('change'));
    </script>
@endsection
