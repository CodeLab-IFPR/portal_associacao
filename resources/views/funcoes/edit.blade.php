@extends('layouts.admin')

@section('title')
Edição - {{ $role->name }}
@endsection('title')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar Função</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container d-flex justify-content-center mt-4">
    <div class="card" style="max-width: 600px; width: 100%;">
        <div class="card-header">
            <h4>Editar Função</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('funcoes.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="inputNomeCargo" class="form-label"><strong>Nome Função:</strong></label>
                    <input type="text" name="name" class="form-control" id="inputNomeCargo" placeholder="Nome Função..."
                        value="{{ old('name', $role->name) }}">
                    @error('name')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="inputPermissoes" class="form-label"><strong>Permissões:</strong></label>
                    <div class="row">
                        @foreach($permissoes as $index => $permissao)
                            <div class="col-md-3 mb-3">
                                <div class="form-check form-check-inline">
                                    <input
                                        {{ ($tem_permissoes->contains($permissao->name)) ? 'checked' : '' }}
                                        type="checkbox" name="permissao[]" id="permissao-{{ $permissao->id }}"
                                        class="form-check-input" value="{{ $permissao->name }}">
                                    <label for="permissao-{{ $permissao->id }}"
                                        class="form-check-label"><strong>{{ $permissao->name }}</strong></label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('funcoes.index') }}" class="btn btn-outline-danger">Voltar</a>
                    <button type="submit" class="btn btn-outline-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection('content')