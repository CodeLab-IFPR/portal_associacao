@extends('layouts.admin')

@section('title')
    Serviço - Criar
@endsection

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Serviço - Cadastro</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                    Serviço - Cadastro
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
    <div class="container d-flex justify-content-center align-items-center">
        <div class="w-50">
            <form action="{{ route('servicos.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="descricao"><strong>Descrição:</strong></label>
                    <input type="text" name="descricao" id="descricao" class="form-control" required>
                </div>
                <div class="form-group mt-4 d-flex justify-content-between">
                <a href="{{ url()->previous() }}" class="btn btn-outline-danger">Voltar</a>
                <button type="submit" class="btn btn-outline-success">Salvar</button>
            </div>
            </form>
        </div>
    </div>
@endsection