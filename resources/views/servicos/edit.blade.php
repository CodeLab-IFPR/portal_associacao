@extends('layouts.admin')

@section('title')
Serviço - Editar
@endsection

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Serviço - Editar</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                    Serviço - Editar
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="d-flex justify-content-center align-items-center">
    <form action="{{ route('servicos.update', $servico->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="descricao"><strong>Descrição:</strong></label>
            <input type="text" name="descricao" id="descricao" class="form-control" value="{{ $servico->descricao }}" required style="width: 500px;">
        </div>
        <div class="form-group mt-4 d-flex justify-content-between">
                <a href="{{ route('servicos.index') }}" class="btn btn-outline-danger">Voltar</a>
                <button type="submit" class="btn btn-outline-success">Salvar</button>
            </div>
    </form>
</div>
@endsection