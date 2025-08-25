@extends('layouts.portal')

@section('content')
<header class="pt-10">
    <div class="container">
        <div class="text-center col-12 col-sm-9 col-lg-7 col-xl-6 mx-auto position-relative z-index-20">
            <h1 class="display-3 fw-bold mb-3">Cadastro de Membro</h1>
            <p class="text-muted lead mb-0">Preencha o formulário abaixo para se cadastrar como membro da associação. Após análise, você receberá um e-mail com a confirmação do cadastro.</p>
        </div>
    </div>
</header>

@php
    $fraseInicio = \App\Models\FraseInicio::first();
@endphp

@if($fraseInicio && $fraseInicio->como_associar_se)
<div class="container position-relative z-index-20 py-3">
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto text-center">
            <button type="button" class="btn btn-outline-primary btn-lg" data-bs-toggle="modal" data-bs-target="#comoAssociarModal">
                <i class="ri-information-line me-2"></i>Como Associar-se?
            </button>
        </div>
    </div>
</div>

<!-- Modal Como Associar-se -->
<div class="modal fade" id="comoAssociarModal" tabindex="-1" aria-labelledby="comoAssociarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="comoAssociarModalLabel">
                    <i class="ri-information-line me-2"></i>Como Associar-se
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-muted">
                    {!! nl2br(e($fraseInicio->como_associar_se)) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
@endif

<div class="container position-relative z-index-20 py-2">
    <div class="row gx-10">
        <div class="col-12 col-lg-8 mx-auto">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('member.register') }}" enctype="multipart/form-data" novalidate>
                @csrf
                
                <!-- Modalidade Principal -->
                <div class="row g-5">
                    <div class="col-12">
                        <h5 class="text-primary fw-bold mb-3">
                            <i class="ri-flight-takeoff-line me-2"></i>Modalidade Principal
                        </h5>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="modalidade_principal" id="aeromodelismo" value="aeromodelismo" {{ old('modalidade_principal') == 'aeromodelismo' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="aeromodelismo">
                                        Aeromodelismo
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="modalidade_principal" id="automodelismo" value="automodelismo" {{ old('modalidade_principal') == 'automodelismo' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="automodelismo">
                                        Automodelismo
                                    </label>
                                </div>
                                @error('modalidade_principal')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Dados do usuário -->
                    <div class="col-12">
                        <h5 class="text-primary fw-bold mb-3">
                            <i class="ri-user-line me-2"></i>Dados do usuário
                        </h5>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="nome">Nome *</label>
                                <input type="text" class="form-control rounded" id="nome" name="nome" value="{{ old('nome') }}" required>
                                @error('nome')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label" for="sobrenome">Sobrenome *</label>
                                <input type="text" class="form-control rounded" id="sobrenome" name="sobrenome" value="{{ old('sobrenome') }}" required>
                                @error('sobrenome')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label" for="data_nascimento">Data de Nascimento</label>
                                <input type="date" class="form-control rounded" id="data_nascimento" name="data_nascimento" value="{{ old('data_nascimento') }}">
                                @error('data_nascimento')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label" for="cpf">CPF *</label>
                                <input type="text" class="form-control rounded" id="cpf" name="cpf" value="{{ old('cpf') }}" required data-mask="cpf">
                                @error('cpf')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="rg">RG *</label>
                                <input type="text" class="form-control rounded" id="rg" name="rg" value="{{ old('rg') }}" required>
                                @error('rg')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Contato -->
                    <div class="col-12">
                        <h5 class="text-primary fw-bold mb-3">
                            <i class="ri-phone-line me-2"></i>Contato
                        </h5>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="telefone_celular">Telefone Celular *</label>
                                <input type="tel" class="form-control rounded" id="telefone_celular" name="telefone_celular" value="{{ old('telefone_celular') }}" required data-mask="phone">
                                @error('telefone_celular')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="celular_whatsapp" name="celular_whatsapp" value="1" {{ old('celular_whatsapp') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="celular_whatsapp">
                                        Celular com WhatsApp?
                                    </label>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label" for="telefone_residencial">Telefone Residencial</label>
                                <input type="tel" class="form-control rounded" id="telefone_residencial" name="telefone_residencial" value="{{ old('telefone_residencial') }}" data-mask="phone">
                                @error('telefone_residencial')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label" for="telefone_comercial">Telefone Comercial</label>
                                <input type="tel" class="form-control rounded" id="telefone_comercial" name="telefone_comercial" value="{{ old('telefone_comercial') }}" data-mask="phone">
                                @error('telefone_comercial')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label" for="email">E-mail *</label>
                                <input type="email" class="form-control rounded" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label" for="email_alternativo">E-mail alternativo</label>
                                <input type="email" class="form-control rounded" id="email_alternativo" name="email_alternativo" value="{{ old('email_alternativo') }}">
                                @error('email_alternativo')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="senha">Senha *</label>
                                <input type="text" class="form-control rounded" id="senha" name="senha" value="{{ old('senha') }}" placeholder="Sua senha será gerada automaticamente">
                                @error('senha')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Endereço -->
                    <div class="col-12">
                        <h5 class="text-primary fw-bold mb-3">
                            <i class="ri-map-pin-line me-2"></i>Endereço
                        </h5>
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="cep">CEP *</label>
                                <input type="text" class="form-control rounded" id="cep" name="cep" value="{{ old('cep') }}" required data-mask="cep">
                            <div id="cep-loading" style="display: none;" class="text-primary small">
                                <i class="fas fa-spinner fa-spin"></i> Buscando endereço...
                            </div>
                            <div id="cep-error" style="display: none;" class="text-danger small mt-1"></div>
                                @error('cep')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-8">
                                <label class="form-label" for="logradouro">Logradouro *</label>
                                <input type="text" class="form-control rounded" id="logradouro" name="logradouro" value="{{ old('logradouro') }}" required>
                                @error('logradouro')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label" for="numero">Número *</label>
                                <input type="text" class="form-control rounded" id="numero" name="numero" value="{{ old('numero') }}" required>
                                @error('numero')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-8">
                                <label class="form-label" for="bairro">Bairro *</label>
                                <input type="text" class="form-control rounded" id="bairro" name="bairro" value="{{ old('bairro') }}" required>
                                @error('bairro')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label" for="estado">Estado *</label>
                                <input type="text" class="form-control rounded" id="estado" name="estado" value="{{ old('estado') }}" required>
                                @error('estado')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label" for="cidade">Cidade *</label>
                                <input type="text" class="form-control rounded" id="cidade" name="cidade" value="{{ old('cidade') }}" required>
                                @error('cidade')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-primary d-flex align-items-center" type="submit" id="submitBtn">
                            <span id="btnText">Enviar Cadastro</span>
                            <span id="btnLoading" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
@vite('resources/js/utils/viacep.js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnLoading = document.getElementById('btnLoading');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            btnText.textContent = 'Enviando...';
            btnLoading.classList.remove('d-none');
            submitBtn.disabled = true;
            setTimeout(() => {
                form.submit();
            }, 500);
        });
    });
</script>
@endpush
@endsection
