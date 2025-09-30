@extends('layouts.portal')

@section('title', 'Validar Cartão AMAER')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0 text-center">
                        <i class="bi bi-shield-check me-2"></i>Validação de Cartão AMAER
                    </h3>
                </div>
                <div class="card-body p-4">
                    
                    @if(!isset($resultado))
                        <!-- Formulário de Validação -->
                        <div class="text-center mb-4">
                            <img src="{{ asset('img/logos/logo-amaer2.png') }}" alt="AMAER" style="max-height: 60px;">
                            <h4 class="mt-3">Validação de Autenticidade</h4>
                            <p class="text-muted">Digite a assinatura eletrônica do cartão para verificar sua autenticidade</p>
                        </div>

                        <form method="POST" action="{{ route('cartao-associado.processar-validacao') }}">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="assinatura" class="form-label fw-bold">Assinatura Eletrônica</label>
                                        <input type="text" 
                                               class="form-control form-control-lg text-center @error('assinatura') is-invalid @enderror" 
                                               id="assinatura" 
                                               name="assinatura" 
                                               placeholder="Ex: 4c37bc5b3d38"
                                               maxlength="12"
                                               value="{{ old('assinatura') }}"
                                               style="font-family: monospace; letter-spacing: 2px;"
                                               required>
                                        @error('assinatura')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            <i class="bi bi-info-circle me-1"></i>
                                            A assinatura eletrônica possui 12 caracteres alfanuméricos e está localizada no rodapé do cartão
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="bi bi-search me-2"></i>Validar Cartão
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <h5>Como usar:</h5>
                            <div class="row g-3 mt-2">
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded">
                                        <i class="bi bi-1-circle text-primary fs-4"></i>
                                        <p class="mt-2 mb-0 small">Localize a assinatura eletrônica no rodapé do cartão</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded">
                                        <i class="bi bi-2-circle text-primary fs-4"></i>
                                        <p class="mt-2 mb-0 small">Digite os 12 caracteres no campo acima</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded">
                                        <i class="bi bi-3-circle text-primary fs-4"></i>
                                        <p class="mt-2 mb-0 small">Clique em "Validar Cartão" para verificar</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @elseif($resultado === 'valido')
                        <!-- Resultado Válido -->
                        <div class="text-center">
                            <div class="alert alert-success" role="alert">
                                <i class="bi bi-check-circle-fill fs-1 text-success"></i>
                                <h4 class="mt-3">✅ Cartão Válido</h4>
                                <p class="mb-0">Este cartão é autêntico e está ativo</p>
                            </div>

                            <!-- Dados do Cartão -->
                            <div class="card mt-4">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">Informações do Associado</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-2"><strong>Nome:</strong> {{ $usuario->name }}</p>
                                            <p class="mb-2"><strong>Matrícula:</strong> 
                                                @if($usuario->matricula)
                                                    {{ $usuario->matricula }}
                                                @else
                                                    AS-{{ str_pad($usuario->id, 6, '0', STR_PAD_LEFT) }}
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-2"><strong>Categoria:</strong> {{ $usuario->categoria ?? 'Aeromodelismo' }}</p>
                                            <p class="mb-2"><strong>Status:</strong> 
                                                <span class="badge bg-success">{{ $usuario->status ?? 'Ativo' }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-2"><strong>Data de Ingresso:</strong> {{ $usuario->created_at->format('d/m/Y') }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-2"><strong>Validade:</strong> {{ $usuario->created_at->addYears(2)->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                    <p class="text-muted mt-2 mb-0">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Assinatura verificada: <code>{{ $assinatura }}</code>
                                    </p>
                                </div>
                            </div>
                        </div>

                    @else
                        <!-- Resultado Inválido -->
                        <div class="text-center">
                            <div class="alert alert-danger" role="alert">
                                <i class="bi bi-x-circle-fill fs-1 text-danger"></i>
                                <h4 class="mt-3">❌ Cartão Inválido</h4>
                                <p class="mb-0">A assinatura eletrônica não foi encontrada ou é inválida</p>
                            </div>

                            <div class="alert alert-warning mt-3" role="alert">
                                <h5 class="alert-heading">Possíveis causas:</h5>
                                <ul class="text-start mb-0">
                                    <li>Assinatura eletrônica digitada incorretamente</li>
                                    <li>Cartão falsificado ou adulterado</li>
                                    <li>Cartão expirado ou inválido</li>
                                </ul>
                            </div>

                            <p class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Assinatura verificada: <code>{{ $assinatura }}</code>
                            </p>
                        </div>
                    @endif

                    @if(isset($resultado))
                        <div class="text-center mt-4">
                            <a href="{{ route('cartao-associado.pagina-validacao') }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left me-2"></i>Validar Outro Cartão
                            </a>
                            <a href="{{ url('/') }}" class="btn btn-primary ms-2">
                                <i class="bi bi-house me-2"></i>Voltar ao Site
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informações Adicionais -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-shield-lock me-2"></i>Sobre a Validação
                    </h5>
                    <p class="card-text">
                        Este sistema de validação permite verificar a autenticidade dos cartões de associado da AMAER 
                        através da assinatura eletrônica única presente em cada cartão. A assinatura é gerada com base 
                        em informações criptografadas do associado e garante a integridade do documento.
                    </p>
                    <p class="card-text">
                        <strong>Associação Maringanese de Aeromodelismo</strong><br>
                        <small class="text-muted">www.amaer.org.br | contato@amaer.org.br</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.btn {
    border-radius: 8px;
}

.form-control {
    border-radius: 8px;
}

.alert {
    border-radius: 10px;
}

code {
    font-size: 1.1em;
    padding: 0.2em 0.4em;
}

.bg-light {
    background-color: #f8f9fa !important;
}
</style>
@endsection