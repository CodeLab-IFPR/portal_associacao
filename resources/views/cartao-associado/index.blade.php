@extends('layouts.admin')

@section('title')
Cartão do Associado
@endsection

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Cartão do Associado</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cartão do Associado</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Seu Cartão AMAER</h3>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <!-- Cartão do Associado -->
                        <div class="cartao-associado mx-auto" style="width: 100%; max-width: 600px; height: 380px; background: #f8f9fa; border-radius: 30px; position: relative; overflow: hidden; color: #333; box-shadow: 0 8px 25px rgba(0,0,0,0.2);">
                            
                            <!-- Header com Logo e Status -->
                            <div style="position: absolute; top: 0; left: 0; right: 0; background: linear-gradient(135deg, #1e88e5, #42a5f5); padding: 15px 25px; display: flex; justify-content: space-between; align-items: center;">
                                <div style="display: flex; align-items: center;">
                                    <img src="{{ asset('img/logos/logo-amaer2.png') }}" alt="AMAER" style="height: 40px; background: rgba(255,255,255,0.9); padding: 8px; border-radius: 8px;">
                                </div>
                                <div style="background: #4caf50; padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: bold; color: white;">
                                    ATIVO
                                </div>
                            </div>

                            <!-- Conteúdo Principal -->
                            <div style="position: absolute; top: 80px; left: 25px; right: 25px; bottom: 60px; display: flex;">
                                
                                <!-- Foto do Associado -->
                                <div style="width: 120px; height: 120px; border-radius: 60px; background: #f5f5f5; border: 4px solid rgba(255,255,255,0.8); overflow: hidden; display: flex; align-items: center; justify-content: center; margin-right: 25px;">
                                    @if($user->imagem && file_exists(public_path('imagens/users/' . $user->imagem)))
                                        <img src="{{ asset('imagens/users/' . $user->imagem) }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <i class="bi bi-person-fill" style="font-size: 60px; color: #999;"></i>
                                    @endif
                                </div>

                                <!-- Informações do Associado -->
                                <div style="flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div>
                                        <h3 style="margin: 0; font-size: 24px; font-weight: bold; margin-bottom: 8px;">{{ $user->name }}</h3>
                                        
                                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                                            <div>
                                                <div style="font-size: 12px; opacity: 0.9; margin-bottom: 2px;">Matrícula</div>
                                                <div style="font-size: 16px; font-weight: bold;">
                                                    @if($user->matricula)
                                                        {{ $user->matricula }}
                                                    @else
                                                        AS-{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}
                                                    @endif
                                                </div>
                                            </div>
                                            <div>
                                                <div style="font-size: 12px; opacity: 0.9; margin-bottom: 2px;">Categoria</div>
                                                <div style="font-size: 16px; font-weight: bold;">
                                                    {{ $user->categoria ?? 'Aeromodelismo' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                            <div>
                                                <div style="font-size: 12px; opacity: 0.9; margin-bottom: 2px;">Ingresso</div>
                                                <div style="font-size: 16px; font-weight: bold;">{{ $user->created_at->format('d/m/Y') }}</div>
                                            </div>
                                            <div>
                                                <div style="font-size: 12px; opacity: 0.9; margin-bottom: 2px;">Validade</div>
                                                <div style="font-size: 16px; font-weight: bold;">{{ $user->created_at->addYears(2)->format('d/m/Y') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- QR Code -->
                                <div style="width: 100px; height: 100px; background: white; border-radius: 10px; padding: 10px; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                                    <div style="text-align: center;">
                                        <img src="{{ $qrUrl }}" style="width: 70px; height: 70px;">
                                        <div style="font-size: 8px; color: #666; margin-top: 2px;">Validar em:<br>amaer.com.br/validar</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer com Assinatura -->
                            <div style="position: absolute; bottom: 0; left: 0; right: 0; background: #1e88e5; padding: 12px 25px; display: flex; justify-content: space-between; align-items: center; font-size: 11px; color: white;">
                                <div>
                                    <div>Assinatura eletrônica: {{ substr(md5($user->email . $user->created_at), 0, 12) }}</div>
                                </div>
                                <div>
                                    <div>Emitido em {{ now()->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Botão de Download -->
                        <div class="text-center mt-4">
                            <a href="{{ route('cartao-associado.download-image') }}" class="btn" style="background-color: #1D3572; color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; font-weight: bold;">
                                <i class="bi bi-download me-2"></i>Baixar Cartão
                            </a>
                        </div>

                        <!-- Informações Adicionais -->
                        <div class="mt-4 p-3 bg-light rounded">
                            <h5>Informações do Cartão</h5>
                            <ul class="mb-0">
                                <li>Este cartão é válido por 1 anos a partir da data de ingresso na associação</li>
                                <li>O QR Code permite validação da autenticidade do cartão</li>
                                <li>Mantenha sempre seu cartão atualizado junto à administração</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .cartao-associado {
        transform: scale(0.8);
        margin: 20px auto !important;
    }
}

@media print {
    .cartao-associado {
        transform: scale(1.2);
        margin: 50px auto;
    }
}
</style>
@endsection