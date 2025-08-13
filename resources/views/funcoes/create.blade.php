@extends('layouts.admin')

@section('title')
Cargos - Criação
@endsection('title')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Novo Cargo</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Novo Cargo</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container d-flex justify-content-center">
    <div class="card-body" style="max-width: 600px;">
        <form action="{{ route('funcoes.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="inputNomeCargo" class="form-label"><strong>Nome Cargo:</strong></label>
                <input type="text" name="name" class="form-control" id="inputNomeCargo"
                    placeholder="Nome Cargo..." value="{{ old('name') }}" >
                @error('name')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                @foreach ($permissoes as $permissao)
                    <div class="form-check form-check-inline mt-1">
                        <input type="checkbox" name="permissao[]" id="permissao-{{$permissao->id}}" class="form-check-input" value="{{$permissao->name}}">
                        <label for="permissao-{{$permissao->id}}" class="form-check-label"><strong>{{$permissao->name}}</strong></label>
                    </div>    
                @endforeach
            </div>
            <div class="d-flex justify-content-between">
                <a href="{{ route('funcoes.index') }}"
                    class="btn btn-outline-danger">Voltar</a>
                <button type="submit" class="btn btn-outline-success">Salvar</button>
            </div>
            </form>
            </div>
            </div>
@endsection('content')