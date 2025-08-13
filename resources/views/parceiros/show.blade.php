@extends('layouts.admin')

<!-- Titulo -->
@section('title')
{{ $parceiro->nome }}
@endsection
<!-- Titulo -->

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Parceiro - Visualização</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Parceiro - Visualização
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <img src="/imagens/parceiros/{{ $parceiro->imagem }}" alt="{{ $parceiro->alt }}" class="img-fluid rounded me-3" width="80px">
                <h4 class="mb-0">{{ $parceiro->nome }}</h4>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Nome:</strong>
                        <p>{{ $parceiro->nome }}</p>
                    </div>
                    <div class="form-group">
                        <strong>E-mail:</strong>
                        <p>{{ $parceiro->email }}</p>
                    </div>
                    <div class="form-group">
                        <strong>URL:</strong>
                        <p><a href="{{ $parceiro->link }}" target="_blank">{{ $parceiro->link }}</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
