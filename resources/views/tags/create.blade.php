@extends('layouts.admin')

@section('title')
Tags - Criar
@endsection

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Adicionar Tags</h3>
                <p>Pressione enter para adicionar mais tags</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Adicionar Tags</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container d-flex justify-content-center">
    <div class="card-body" style="max-width: 600px;">
        <form action="{{ route('tags.store') }}" method="POST">
            @csrf

            <div id="tags-container">
                <div class="mb-3">
                    <label for="inputTag1" class="form-label"><strong>Tags:</strong></label>
                    <input type="text" name="tags[]" class="form-control" id="inputTag1" placeholder="Digite uma tag...">
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('tags.index') }}" class="btn btn-outline-danger">Voltar</a>
                <button type="submit" class="btn btn-outline-success">Salvar</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tagsContainer = document.getElementById('tags-container');

        tagsContainer.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();

                const inputs = tagsContainer.querySelectorAll('input');
                inputs.forEach(input => {
                    if (input.value.trim() === '') {
                        input.parentElement.remove();
                    }
                });

                const newTagInput = document.createElement('div');
                newTagInput.classList.add('mb-3');
                newTagInput.innerHTML = `
                    <input type="text" name="tags[]" class="form-control" placeholder="Digite uma tag...">
                `;

                tagsContainer.appendChild(newTagInput);

                // Move focus to the newly created input field
                const newInput = newTagInput.querySelector('input');
                newInput.focus();
            }
        });
    });
</script>
@endsection
