@extends('layouts.admin')

<!-- Titulo -->
@section('title')
{{ $user->nome ?? $user->name }} {{ $user->sobrenome }}
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
                <div class="me-3">
                    <div style="width: 80px; height: 80px; border-radius: 50%; background-color: #6c757d; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user text-white fa-2x"></i>
                    </div>
                </div>
                <h4 class="mb-0">{{ $user->nome ?? $user->name }} {{ $user->sobrenome }}</h4>
            </div>
        </div>
        <div class="card-body">
            <!-- Modalidade Principal -->
            @if($user->modalidade_principal)
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="text-primary fw-bold mb-3">
                        <i class="ri-flight-takeoff-line me-2"></i>Modalidade Principal
                    </h5>
                    <p class="mb-0">
                        <span class="badge bg-info">{{ ucfirst($user->modalidade_principal) }}</span>
                    </p>
                </div>
            </div>
            @endif

            <!-- Dados Pessoais -->
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="text-primary fw-bold mb-3">
                        <i class="ri-user-line me-2"></i>Dados Pessoais
                    </h5>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <strong>Nome:</strong>
                        <p class="mb-0">{{ $user->nome ?? $user->name }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <strong>Sobrenome:</strong>
                        <p class="mb-0">{{ $user->sobrenome }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <strong>Nome Completo (legado):</strong>
                        <p class="mb-0">{{ $user->nome_completo_legado }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <strong>Data de Nascimento:</strong>
                        <p class="mb-0">{{ $user->data_nascimento ? \Carbon\Carbon::parse($user->data_nascimento)->format('d/m/Y') : 'Não informado' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <strong>CPF:</strong>
                        <p class="mb-0">{{ $user->cpf }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <strong>RG:</strong>
                        <p class="mb-0">{{ $user->rg }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <strong>Ativo:</strong>
                        <p class="mb-0">
                            @if($user->ativo)
                                <span class="badge bg-success">Sim</span>
                            @else
                                <span class="badge bg-danger">Não</span>
                            @endif
                        </p>
                    </div>
                    <div class="form-group mb-3">
                        <strong>Função:</strong>
                        <p class="mb-0">
                            @if (!empty($user->getRoleNames()))
                                @foreach ($user->getRoleNames() as $role)
                                    <span class="badge bg-primary mx-1">{{ $role }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">Nenhuma função atribuída</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contato -->
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="text-primary fw-bold mb-3">
                        <i class="ri-phone-line me-2"></i>Contato
                    </h5>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <strong>Email:</strong>
                        <p class="mb-0">{{ $user->email }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <strong>Email Alternativo:</strong>
                        <p class="mb-0">{{ $user->email_alternativo ?? 'Não informado' }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <strong>Telefone Celular:</strong>
                        <p class="mb-0">
                            {{ $user->telefone_celular }}
                            @if($user->celular_whatsapp)
                                <span class="badge bg-success ms-2">
                                    <i class="fab fa-whatsapp"></i> WhatsApp
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <strong>Telefone Residencial:</strong>
                        <p class="mb-0">{{ $user->telefone_residencial ?? 'Não informado' }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <strong>Telefone Comercial:</strong>
                        <p class="mb-0">{{ $user->telefone_comercial ?? 'Não informado' }}</p>
                    </div>
                </div>
            </div>

            <!-- Endereço -->
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="text-primary fw-bold mb-3">
                        <i class="ri-map-pin-line me-2"></i>Endereço
                    </h5>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <strong>CEP:</strong>
                        <p class="mb-0">{{ $user->cep }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <strong>Logradouro:</strong>
                        <p class="mb-0">{{ $user->logradouro }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <strong>Número:</strong>
                        <p class="mb-0">{{ $user->numero }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <strong>Bairro:</strong>
                        <p class="mb-0">{{ $user->bairro }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <strong>Cidade:</strong>
                        <p class="mb-0">{{ $user->cidade }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <strong>Estado:</strong>
                        <p class="mb-0">{{ $user->estado }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection