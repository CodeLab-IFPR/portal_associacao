@extends('layouts.admin')

<!-- Titulo -->
@section('title')
Membros - Cadastro
@endsection
<!-- Titulo -->

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Membros - Cadastro</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Membros - Cadastro
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container pt-2 d-flex justify-content-center">
    <div class="card-body p-4 p-lg-5 rounded-4 shadow-sm bg-white" style="max-width: 1000px; width: 100%;">
        <header class="text-center mb-4">
            <h2 class="fs-4 fw-medium mb-3">{{ __('Cadastro de Novo Membro') }}</h2>
            <p class="text-muted">{{ __("Preencha as informações para cadastrar um novo membro da associação.") }}</p>
        </header>

        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Modalidade Principal -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-star"></i> Modalidade Principal</h5>
                </div>
                <div class="card-body">
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
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Dados do usuário -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Dados do Usuário</h5>
                </div>
                <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputNome" class="form-label"><strong>*Nome:</strong></label>
                            <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror" id="inputNome"
                                placeholder="Nome..." value="{{ old('nome') }}" required>
                            @error('nome')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputSobrenome" class="form-label"><strong>*Sobrenome:</strong></label>
                            <input type="text" name="sobrenome" class="form-control @error('sobrenome') is-invalid @enderror" id="inputSobrenome"
                                placeholder="Sobrenome..." value="{{ old('sobrenome') }}" required>
                            @error('sobrenome')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputDataNascimento" class="form-label"><strong>Data de Nascimento:</strong></label>
                            <input type="date" name="data_nascimento" class="form-control @error('data_nascimento') is-invalid @enderror" id="inputDataNascimento" value="{{ old('data_nascimento') }}">
                            @error('data_nascimento')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputCpf" class="form-label"><strong>*CPF:</strong></label>
                                                        <input type="text" class="form-control @error('cpf') is-invalid @enderror" name="cpf" id="inputCpf" maxlength="14"
                                placeholder="CPF..." value="{{ old('cpf') }}">
                            @error('cpf')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="inputRg" class="form-label"><strong>*RG:</strong></label>
                    <input type="text" class="form-control @error('rg') is-invalid @enderror" name="rg" id="inputRg"
                        placeholder="RG..." value="{{ old('rg') }}" required>
                    @error('rg')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="inputCargo" class="form-label"><strong>Cargo:</strong></label>
                    <select name="cargo_id" class="form-control @error('cargo_id') is-invalid @enderror" id="inputCargo">
                        <option value="">Selecione um cargo (opcional)</option>
                        @foreach(\App\Models\Cargo::all() as $cargo)
                            <option value="{{ $cargo->id }}" {{ old('cargo_id') == $cargo->id ? 'selected' : '' }}>
                                {{ $cargo->descricao }}
                            </option>
                        @endforeach
                    </select>
                    @error('cargo_id')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>
                </div>
            </div>

            <!-- Contato -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-phone"></i> Contato</h5>
                </div>
                <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputTelefoneCelular" class="form-label"><strong>*Telefone Celular:</strong></label>
                            <input type="tel" name="telefone_celular" class="form-control @error('telefone_celular') is-invalid @enderror" id="inputTelefoneCelular" maxlength="15"
                                placeholder="Telefone Celular..." value="{{ old('telefone_celular') }}" required>
                            @error('telefone_celular')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="celularWhatsapp" name="celular_whatsapp" value="1" {{ old('celular_whatsapp') ? 'checked' : '' }}>
                            <label class="form-check-label" for="celularWhatsapp">
                                <strong>Celular com WhatsApp?</strong>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputTelefoneResidencial" class="form-label"><strong>Telefone Residencial:</strong></label>
                            <input type="tel" name="telefone_residencial" class="form-control @error('telefone_residencial') is-invalid @enderror" id="inputTelefoneResidencial" maxlength="15"
                                placeholder="Telefone Residencial..." value="{{ old('telefone_residencial') }}">
                            @error('telefone_residencial')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputTelefoneComercial" class="form-label"><strong>Telefone Comercial:</strong></label>
                            <input type="tel" name="telefone_comercial" class="form-control @error('telefone_comercial') is-invalid @enderror" id="inputTelefoneComercial" maxlength="15"
                                placeholder="Telefone Comercial..." value="{{ old('telefone_comercial') }}">
                            @error('telefone_comercial')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputEmail" class="form-label"><strong>*Email:</strong></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="inputEmail"
                                placeholder="Email..." value="{{ old('email') }}" required>
                            @error('email')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputEmailAlternativo" class="form-label"><strong>Email Alternativo:</strong></label>
                            <input type="email" name="email_alternativo" class="form-control @error('email_alternativo') is-invalid @enderror" id="inputEmailAlternativo"
                                placeholder="Email Alternativo..." value="{{ old('email_alternativo') }}">
                            @error('email_alternativo')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="inputSenha" class="form-label"><strong>Senha:</strong></label>
                    <input type="text" class="form-control @error('senha') is-invalid @enderror" name="senha" id="inputSenha"
                        placeholder="Senha será gerada automaticamente" value="{{ old('senha') }}">
                    @error('senha')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>
                </div>
            </div>

            <!-- Endereço -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Endereço</h5>
                </div>
                <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="inputCep" class="form-label"><strong>*CEP:</strong></label>
                            <input type="text" name="cep" class="form-control @error('cep') is-invalid @enderror" id="cep" maxlength="8"
                                placeholder="CEP..." value="{{ old('cep') }}" required>
                            @error('cep')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="inputLogradouro" class="form-label"><strong>*Logradouro:</strong></label>
                            <input type="text" name="logradouro" class="form-control @error('logradouro') is-invalid @enderror" id="logradouro"
                                placeholder="Logradouro..." value="{{ old('logradouro') }}" required>
                            @error('logradouro')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="inputNumero" class="form-label"><strong>*Número:</strong></label>
                            <input type="text" name="numero" class="form-control @error('numero') is-invalid @enderror" id="inputNumero"
                                placeholder="Número..." value="{{ old('numero') }}" required>
                            @error('numero')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="inputBairro" class="form-label"><strong>*Bairro:</strong></label>
                            <input type="text" name="bairro" class="form-control @error('bairro') is-invalid @enderror" id="bairro"
                                placeholder="Bairro..." value="{{ old('bairro') }}" required>
                            @error('bairro')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputEstado" class="form-label"><strong>*Estado:</strong></label>
                            <input type="text" name="estado" class="form-control @error('estado') is-invalid @enderror" id="estado"
                                placeholder="Estado..." value="{{ old('estado') }}" required>
                            @error('estado')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputCidade" class="form-label"><strong>*Cidade:</strong></label>
                            <input type="text" name="cidade" class="form-control @error('cidade') is-invalid @enderror" id="cidade"
                                placeholder="Cidade..." value="{{ old('cidade') }}" required>
                            @error('cidade')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                </div>
            </div>

            <!-- Campos administrativos -->
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-cog"></i> Configurações Administrativas</h5>
                </div>
                <div class="card-body">
                <div class="mb-3">
                    <label for="inputAtivo" class="form-label"><strong>Status:</strong></label>
                    <div class="form-check">
                        <input type="checkbox" name="ativo" class="form-check-input @error('ativo') is-invalid @enderror" id="inputAtivo" value="1" {{ old('ativo') ? 'checked' : '' }}>
                        <label class="form-check-label" for="inputAtivo">
                            <strong>Usuário Ativo</strong>
                        </label>
                    </div>
                    @error('ativo')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label"><strong>Funções/Permissões:</strong></label>
                    <input type="hidden" name="roles[]" value="" required>
                    @foreach ($roles as $role)
                        <div class="form-check form-check-inline mt-1">
                            <input type="checkbox" name="roles[]" id="role-{{$role->id}}" class="form-check-input" value="{{ $role->name }}">
                            <label for="role-{{$role->id}}"><strong>{{$role->name}}</strong></label>
                        </div>
                    @endforeach
                    @error('roles')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>
                </div>
            </div>

            <input type="hidden" name="generated_password" id="generated_password" value="">

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <a href="{{ route('users.index') }}" class="btn btn-secondary me-md-2">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Salvar Membro
                </button>
            </div>
        </form>
    </div>
</div>

@vite('resources/js/utils/viacep.js')
<script>
// Função para aplicar máscara de CPF
function applyCPFMask() {
    const cpfInput = document.getElementById('inputCpf');
    if (cpfInput) {
        cpfInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            e.target.value = value;
        });
    }
}

// Função para aplicar máscara de telefone
function applyPhoneMask() {
    const phoneInputs = [
        document.getElementById('inputTelefoneCelular'),
        document.getElementById('inputTelefoneResidencial'), 
        document.getElementById('inputTelefoneComercial')
    ];
    
    phoneInputs.forEach(phoneInput => {
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 10) {
                    value = value.replace(/(\d{2})(\d)/, '($1) $2');
                    value = value.replace(/(\d{4})(\d)/, '$1-$2');
                } else {
                    value = value.replace(/(\d{2})(\d)/, '($1) $2');
                    value = value.replace(/(\d{5})(\d)/, '$1-$2');
                }
                e.target.value = value;
            });
        }
    });
}

// Função para aplicar máscara de CEP
function applyCEPMask() {
    const cepInput = document.getElementById('inputCep');
    if (cepInput) {
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var password = Math.random().toString(36).slice(-8);
    document.getElementById('generated_password').value = password;
    
    // Aplicar máscaras
    applyCPFMask();
    applyPhoneMask();
    applyCEPMask();
});
</script>
@endsection