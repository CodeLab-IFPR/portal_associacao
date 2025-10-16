@extends('layouts.admin')

@section('title', 'Visualizar Mensagem')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Visualizar Mensagem</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('mensagens.index') }}">Mensagens</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Visualizar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12" style="max-width: 900px;">
                
                <!-- Ações -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-cogs"></i> Ações
                        </h6>
                    </div>
                    <div class="card-body py-2">
                        <div class="btn-group" role="group">
                            <a href="{{ route('mensagens.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Voltar à Lista
                            </a>
                            <form method="POST" action="{{ route('mensagens.destroy', $mensagem->id) }}" 
                                  style="display: inline;"
                                  onsubmit="return confirm('Tem certeza que deseja excluir esta mensagem?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-trash"></i> Excluir Mensagem
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Dados da Mensagem -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-envelope-open"></i> Detalhes da Mensagem
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Informações do Remetente -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-user"></i> Informações do Remetente
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nome:</label>
                                    <p class="form-control-plaintext">{{ $mensagem->name ?? $mensagem->nome ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email:</label>
                                    <p class="form-control-plaintext">
                                        @if($mensagem->email)
                                            <a href="mailto:{{ $mensagem->email }}" class="text-decoration-none">
                                                {{ $mensagem->email }}
                                            </a>
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Detalhes da Mensagem -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-envelope"></i> Detalhes da Mensagem
                                </h6>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Assunto:</label>
                                    <p class="form-control-plaintext">{{ $mensagem->subject ?? $mensagem->assunto ?? 'Sem assunto' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Data de Recebimento:</label>
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-calendar-alt me-1 text-muted"></i>
                                        {{ $mensagem->created_at->format('d/m/Y') }}
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $mensagem->created_at->format('H:i') }}
                                        </small>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Conteúdo da Mensagem -->
                        <div class="row">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-comment"></i> Conteúdo da Mensagem
                                </h6>
                                <div class="bg-light p-4 rounded">
                                    <div style="white-space: pre-wrap; line-height: 1.6;">{{ $mensagem->message ?? $mensagem->mensagem ?? 'Sem conteúdo' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .form-control-plaintext {
        padding-left: 0;
        margin-bottom: 0;
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
@endpush
