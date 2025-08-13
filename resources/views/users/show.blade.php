@extends('layouts.admin')

<!-- Titulo -->
@section('title')
{{ $user->name }}
@endsection
<!-- Titulo -->

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Membro - Visualização</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Membro - Visualização
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
                <img src="/imagens/users/{{ $user->imagem }}" alt="{{ $user->alt }}" class="img-fluid rounded me-3" width="80px">
                <h4 class="mb-0">{{ $user->name }}</h4>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Email:</strong>
                        <p>{{ $user->email }}</p>
                    </div>
                    <div class="form-group">
                        <strong>Nome:</strong>
                        <p>{{ $user->name }}</p>
                    </div>
                    <div class="form-group">
                        <strong>Cargo:</strong>
                        <p>{{ $user->cargo }}</p>
                    </div>
                    <div class="form-group">
                        <strong>Biografia:</strong>
                        <p>{{ $user->biografia }}</p>
                    </div>
                    <div class="form-group">
                        <strong>LinkedIn:</strong>
                        <p><a href="{{ $user->linkedin }}" target="_blank">{{ $user->linkedin }}</a></p>
                    </div>
                    <div class="form-group">
                        <strong>GitHub:</strong>
                        <p><a href="{{ $user->github }}" target="_blank">{{ $user->github }}</a></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>CPF:</strong>
                        <p>{{ $user->cpf }}</p>
                    </div>
                    <div class="form-group">
                        <strong>Ativo:</strong>
                        <p>{{ $user->ativo ? 'Sim' : 'Não' }}</p>
                    </div>
                    <div class="form-group">
                        <strong>Função:</strong>
                        @if (!empty($user->getRoleNames()))
                        @foreach ($user->getRoleNames() as $role)
                            <span class="badge bg-primary mx-1">{{ $role }}</span>
                        @endforeach
                
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection