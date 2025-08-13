@extends('layouts.admin')

@section('title')
Edição - {{ $permissao->name }}
@endsection('title')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Editar Permissão</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar Permissão</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container d-flex justify-content-center">
    <div class="card-body" style="max-width: 600px;">
        <form action="{{ route('permissoes.update', $permissao->id ) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="inputNomePermissao" class="form-label"><strong>Nome Permissão:</strong></label>
                <input type="text" name="name" class="form-control" id="inputNomePermissao"
                    placeholder="Nome Permissão..." value="{{ old('name', $permissao->name ) }}" >
                @error('name')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-flex justify-content-between">
                <a href="{{ route('permissoes.index') }}"
                    class="btn btn-outline-danger">Voltar</a>
                <button type="submit" class="btn btn-outline-success">Salvar</button>
            </div>
            </form>
            </div>
            </div>
@endsection('content')