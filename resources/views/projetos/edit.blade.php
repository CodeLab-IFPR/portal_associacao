@extends('layouts.admin')

@section('title')
Editar - {{ $projeto->nome }}
@endsection

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Editar Projeto</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar Projeto</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container d-flex justify-content-center">
    <div class="card-body" style="max-width: 600px; width: 100%;">
        <form action="{{ route('projetos.update', $projeto->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="inputNome" class="form-label"><strong>Nome:</strong></label>
                <input type="text" name="nome" value="{{ old('nome', $projeto->nome) }}" class="form-control @error('nome') inválido @enderror" id="inputNome" placeholder="Nome...">
                @error('nome')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="inputDescricao" class="form-label"><strong>Descrição:</strong></label>
                <textarea name="descricao" class="form-control @error('descricao') inválido @enderror" id="inputDescricao" placeholder="Descrição...">{{ old('descricao', $projeto->descricao) }}</textarea>
                @error('descricao')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="inputStatus" class="form-label"><strong>Status:</strong></label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="status" id="inputStatus" {{ $projeto->status == 'concluido' ? 'checked' : '' }}>
                    <label class="form-check-label" for="inputStatus"></label>
                        Concluído
                    </label>
                </div>
            </div>

            <div class="mb-3">
                <label for="inputTags" class="form-label"><strong>Tags:</strong></label>
                <select name="tags[]" id="inputTags" class="form-control border-primary @error('tags') inválido @enderror" multiple>
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}" {{ in_array($tag->id, $projeto->tags->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $tag->name }}</option>
                    @endforeach
                </select>
                @error('tags')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    $('#inputTags').select2({
                        placeholder: "Selecione tags",
                        allowClear: true,
                        closeOnSelect: false 
                    });
                });
            </script>

            <div class="mb-3">
                <label for="inputImagem" class="form-label"><strong>Imagem:</strong></label>
                <input type="file" name="imagem" class="form-control @error('imagem') inválido @enderror" id="inputImagem" accept="image/*">
                @error('imagem')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
                <div class="mt-3">
                    <img id="imagePreview" src="{{ asset('storage/' . $projeto->imagem) }}" style="max-width: 100%; display: block;">
                </div>
            </div>

            <script>
                document.getElementById('inputImagem').addEventListener('change', function (event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const imagePreview = document.getElementById('imagePreview');
                            imagePreview.src = e.target.result;
                            imagePreview.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    }
                });
            </script>

            <div class="d-flex justify-content-between">
                <a href="{{ route('projetos.index') }}"
                    class="btn btn-outline-danger">Voltar</a>
                <button type="submit" class="btn btn-outline-success">Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
