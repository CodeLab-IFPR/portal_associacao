@extends('layouts.admin')

@section('title')
Galeria
@endsection

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('galeria.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="tipo" class="form-label"><strong>Tipo:*</strong></label>
                            <select class="form-select @error('tipo') is-invalid @enderror" 
                                    id="tipo" name="tipo" required>
                                <option selected disabled>Selecione o tipo</option>
                                <option value="imagem" {{ old('tipo') == 'imagem' ? 'selected' : '' }}>Imagem</option>
                                <option value="video" {{ old('tipo') == 'video' ? 'selected' : '' }}>Vídeo do YouTube</option>
                            </select>
                            @error('tipo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="titulo" class="form-label"><strong>Título:*</strong></label>
                            <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                                   id="titulo" name="titulo" value="{{ old('titulo') }}" required>
                            @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label"><strong>Descrição:</strong></label>
                            <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                      id="descricao" name="descricao" rows="3">{{ old('descricao') }}</textarea>
                            @error('descricao')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="file-input" class="mb-3" style="display: none;">
                            <label for="file" class="form-label"><strong>Arquivo:*</strong></label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                   id="file" name="file" accept="image/*">
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="link-input" class="mb-3" style="display: none;">
                            <label for="link" class="form-label"><strong>Link do YouTube:*</strong></label>
                            <input type="url" class="form-control @error('link') is-invalid @enderror" 
                                   id="link" name="link" value="{{ old('link') }}" 
                                   placeholder="https://www.youtube.com/watch?v=...">
                            @error('link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                <a href="{{ route('galeria.indexAdmin') }}"
                    class="btn btn-outline-danger">Voltar</a>
                <button type="submit" class="btn btn-outline-success">Salvar</button>
            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('tipo').addEventListener('change', function() {
    const fileInput = document.getElementById('file-input');
    const linkInput = document.getElementById('link-input');
    
    if (this.value === 'imagem') {
        fileInput.style.display = 'block';
        linkInput.style.display = 'none';
        document.getElementById('file').required = true;
        document.getElementById('link').required = false;
    } else if (this.value === 'video') {
        fileInput.style.display = 'none';
        linkInput.style.display = 'block';
        document.getElementById('file').required = false;
        document.getElementById('link').required = true;
    } else {
        fileInput.style.display = 'none';
        linkInput.style.display = 'none';
        document.getElementById('file').required = false;
        document.getElementById('link').required = false;
    }
});

// Trigger the change event on page load if there's a selected value
if (document.getElementById('tipo').value) {
    document.getElementById('tipo').dispatchEvent(new Event('change'));
}
</script>
@endsection