@extends('layouts.admin')

@section('title', 'Editar Perfil')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Editar Perfil</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar Perfil</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container pt-2 d-flex justify-content-center">
    <div class="card-body p-4 p-lg-5 rounded-4 shadow-sm bg-white" style="max-width: 900px; width: 100%;">
        <section>
            <!-- Seção da Imagem de Perfil -->
            <div class="text-center mb-4 position-relative">
                <div class="d-inline-block position-relative">
                    @if($user->imagem && !old('cropped_image'))
                        <img src="/imagens/users/{{ $user->imagem }}" width="160px" height="160px" class="rounded-circle" style="object-fit: cover;">
                    @endif
                    <div id="croppedImageContainer" style="{{ old('cropped_image') ? '' : 'display: none;' }}">
                        <div id="croppedImagePreview" style="width: 160px; height: 160px; border: 1px solid #ddd; border-radius: 50%; overflow: hidden;">
                            <img id="croppedImage" src="{{ old('cropped_image') }}" alt="Imagem recortada" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    </div>
                    <!-- Ícone de lápis sobreposto -->
                    <label for="inputImagem" class="position-absolute bottom-0 end-0 bg-white rounded-circle p-2 shadow-sm" style="cursor: pointer; margin-right: -10px; margin-bottom: -5px;">
                        <i class="fas fa-pencil-alt text-primary"></i>
                    </label>
                    <input type="file" name="imagem" class="d-none image" id="inputImagem">
                </div>
                @error('imagem')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <header class="text-center mb-4">
                <h2 class="fs-4 fw-medium mb-3">{{ __('Informações do Perfil') }}</h2>
                <p class="text-muted">{{ __("Atualize as informações do perfil e endereço de email da sua conta.") }}</p>
            </header>

            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                @csrf
            </form>

            <form method="post" action="{{ route('profile.update') }}" class="mt-4" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <input type="hidden" name="cropped_image" id="cropped_image" value="{{ old('cropped_image') }}">
                
                <!-- Modalidade Principal -->
                <div class="mb-4">
                    <h5 class="text-primary mb-3"><strong>Modalidade Principal:</strong></h5>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="modalidade_principal" id="aeromodelismo" value="aeromodelismo" {{ old('modalidade_principal', $user->modalidade_principal) == 'aeromodelismo' ? 'checked' : '' }}>
                        <label class="form-check-label" for="aeromodelismo">
                            Aeromodelismo
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="modalidade_principal" id="automodelismo" value="automodelismo" {{ old('modalidade_principal', $user->modalidade_principal) == 'automodelismo' ? 'checked' : '' }}>
                        <label class="form-check-label" for="automodelismo">
                            Automodelismo
                        </label>
                    </div>
                </div>

                <!-- Dados do Usuário -->
                <h5 class="text-primary mb-3"><strong>Dados do usuário:</strong></h5>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label"><strong>Nome:</strong></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="sobrenome" class="form-label"><strong>Sobrenome:</strong></label>
                            <input type="text" name="sobrenome" class="form-control @error('sobrenome') is-invalid @enderror" id="sobrenome" value="{{ old('sobrenome', $user->sobrenome) }}" required>
                            @error('sobrenome')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label"><strong>Email:</strong></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="form-text text-warning">
                                    {{ __('Seu endereço de email não foi verificado.') }}
                                    <button form="send-verification" class="btn btn-link p-0 text-decoration-underline">
                                        {{ __('Clique aqui para reenviar o email de verificação.') }}
                                    </button>
                                </div>
                                @if (session('status') === 'verification-link-sent')
                                    <div class="form-text text-success">
                                        {{ __('Um novo link de verificação foi enviado para seu endereço de email.') }}
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="cpf" class="form-label"><strong>CPF:</strong></label>
                            <input type="text" name="cpf" class="form-control @error('cpf') is-invalid @enderror" id="cpf" value="{{ old('cpf', $user->cpf) }}" maxlength="14" required>
                            @error('cpf')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="telefone_celular" class="form-label"><strong>Telefone Celular:</strong></label>
                            <input type="text" name="telefone_celular" class="form-control @error('telefone_celular') is-invalid @enderror" id="telefone_celular" value="{{ old('telefone_celular', $user->telefone_celular) }}" maxlength="15">
                            @error('telefone_celular')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="telefone_residencial" class="form-label"><strong>Telefone Residencial:</strong></label>
                            <input type="text" name="telefone_residencial" class="form-control @error('telefone_residencial') is-invalid @enderror" id="telefone_residencial" value="{{ old('telefone_residencial', $user->telefone_residencial) }}" maxlength="15">
                            @error('telefone_residencial')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="telefone_comercial" class="form-label"><strong>Telefone Comercial:</strong></label>
                            <input type="text" name="telefone_comercial" class="form-control @error('telefone_comercial') is-invalid @enderror" id="telefone_comercial" value="{{ old('telefone_comercial', $user->telefone_comercial) }}" maxlength="15">
                            @error('telefone_comercial')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="data_nascimento" class="form-label"><strong>Data de Nascimento:</strong></label>
                            <input type="date" name="data_nascimento" class="form-control @error('data_nascimento') is-invalid @enderror" id="data_nascimento" value="{{ old('data_nascimento', $user->data_nascimento) }}">
                            @error('data_nascimento')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="profissao" class="form-label"><strong>Profissão:</strong></label>
                            <input type="text" name="profissao" class="form-control @error('profissao') is-invalid @enderror" id="profissao" value="{{ old('profissao', $user->profissao) }}">
                            @error('profissao')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="escolaridade" class="form-label"><strong>Escolaridade:</strong></label>
                            <select name="escolaridade" class="form-select @error('escolaridade') is-invalid @enderror" id="escolaridade">
                                <option value="">Selecione...</option>
                                <option value="Ensino Fundamental" {{ old('escolaridade', $user->escolaridade) == 'Ensino Fundamental' ? 'selected' : '' }}>Ensino Fundamental</option>
                                <option value="Ensino Médio" {{ old('escolaridade', $user->escolaridade) == 'Ensino Médio' ? 'selected' : '' }}>Ensino Médio</option>
                                <option value="Ensino Superior" {{ old('escolaridade', $user->escolaridade) == 'Ensino Superior' ? 'selected' : '' }}>Ensino Superior</option>
                                <option value="Pós-graduação" {{ old('escolaridade', $user->escolaridade) == 'Pós-graduação' ? 'selected' : '' }}>Pós-graduação</option>
                                <option value="Mestrado" {{ old('escolaridade', $user->escolaridade) == 'Mestrado' ? 'selected' : '' }}>Mestrado</option>
                                <option value="Doutorado" {{ old('escolaridade', $user->escolaridade) == 'Doutorado' ? 'selected' : '' }}>Doutorado</option>
                            </select>
                            @error('escolaridade')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Dados de Endereço -->
                <h5 class="text-primary mb-3 mt-4"><strong>Dados de Endereço:</strong></h5>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="cep" class="form-label"><strong>CEP:</strong></label>
                            <input type="text" name="cep" class="form-control @error('cep') is-invalid @enderror" id="cep" value="{{ old('cep', $user->cep) }}" maxlength="9">
                            <div id="cep-error" class="form-text text-danger" style="display: none;"></div>
                            @error('cep')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="endereco" class="form-label"><strong>Endereço:</strong></label>
                            <input type="text" name="endereco" class="form-control @error('endereco') is-invalid @enderror" id="endereco" value="{{ old('endereco', $user->endereco) }}">
                            @error('endereco')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="numero" class="form-label"><strong>Número:</strong></label>
                            <input type="text" name="numero" class="form-control @error('numero') is-invalid @enderror" id="numero" value="{{ old('numero', $user->numero) }}">
                            @error('numero')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="mb-3">
                            <label for="complemento" class="form-label"><strong>Complemento:</strong></label>
                            <input type="text" name="complemento" class="form-control @error('complemento') is-invalid @enderror" id="complemento" value="{{ old('complemento', $user->complemento) }}">
                            @error('complemento')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="bairro" class="form-label"><strong>Bairro:</strong></label>
                            <input type="text" name="bairro" class="form-control @error('bairro') is-invalid @enderror" id="bairro" value="{{ old('bairro', $user->bairro) }}">
                            @error('bairro')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="cidade" class="form-label"><strong>Cidade:</strong></label>
                            <input type="text" name="cidade" class="form-control @error('cidade') is-invalid @enderror" id="cidade" value="{{ old('cidade', $user->cidade) }}">
                            @error('cidade')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="estado" class="form-label"><strong>Estado:</strong></label>
                    <select name="estado" class="form-select @error('estado') is-invalid @enderror" id="estado">
                        <option value="">Selecione...</option>
                        <option value="AC" {{ old('estado', $user->estado) == 'AC' ? 'selected' : '' }}>Acre</option>
                        <option value="AL" {{ old('estado', $user->estado) == 'AL' ? 'selected' : '' }}>Alagoas</option>
                        <option value="AP" {{ old('estado', $user->estado) == 'AP' ? 'selected' : '' }}>Amapá</option>
                        <option value="AM" {{ old('estado', $user->estado) == 'AM' ? 'selected' : '' }}>Amazonas</option>
                        <option value="BA" {{ old('estado', $user->estado) == 'BA' ? 'selected' : '' }}>Bahia</option>
                        <option value="CE" {{ old('estado', $user->estado) == 'CE' ? 'selected' : '' }}>Ceará</option>
                        <option value="DF" {{ old('estado', $user->estado) == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                        <option value="ES" {{ old('estado', $user->estado) == 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                        <option value="GO" {{ old('estado', $user->estado) == 'GO' ? 'selected' : '' }}>Goiás</option>
                        <option value="MA" {{ old('estado', $user->estado) == 'MA' ? 'selected' : '' }}>Maranhão</option>
                        <option value="MT" {{ old('estado', $user->estado) == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                        <option value="MS" {{ old('estado', $user->estado) == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                        <option value="MG" {{ old('estado', $user->estado) == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                        <option value="PA" {{ old('estado', $user->estado) == 'PA' ? 'selected' : '' }}>Pará</option>
                        <option value="PB" {{ old('estado', $user->estado) == 'PB' ? 'selected' : '' }}>Paraíba</option>
                        <option value="PR" {{ old('estado', $user->estado) == 'PR' ? 'selected' : '' }}>Paraná</option>
                        <option value="PE" {{ old('estado', $user->estado) == 'PE' ? 'selected' : '' }}>Pernambuco</option>
                        <option value="PI" {{ old('estado', $user->estado) == 'PI' ? 'selected' : '' }}>Piauí</option>
                        <option value="RJ" {{ old('estado', $user->estado) == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                        <option value="RN" {{ old('estado', $user->estado) == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                        <option value="RS" {{ old('estado', $user->estado) == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                        <option value="RO" {{ old('estado', $user->estado) == 'RO' ? 'selected' : '' }}>Rondônia</option>
                        <option value="RR" {{ old('estado', $user->estado) == 'RR' ? 'selected' : '' }}>Roraima</option>
                        <option value="SC" {{ old('estado', $user->estado) == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                        <option value="SP" {{ old('estado', $user->estado) == 'SP' ? 'selected' : '' }}>São Paulo</option>
                        <option value="SE" {{ old('estado', $user->estado) == 'SE' ? 'selected' : '' }}>Sergipe</option>
                        <option value="TO" {{ old('estado', $user->estado) == 'TO' ? 'selected' : '' }}>Tocantins</option>
                    </select>
                    @error('estado')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('admin') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                    <a href="{{ route('password.custom.edit') }}" class="btn btn-outline-warning me-2">
                        <i class="fas fa-key"></i> Alterar Senha
                    </a>
                    <button type="submit" class="btn btn-outline-success">
                        <i class="fas fa-save"></i> {{ __('Salvar') }}
                    </button>
                </div>

                @if (session('status') === 'profile-updated')
                    <div class="alert alert-success mt-3 d-flex align-items-center" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <div>
                            <strong>Sucesso!</strong> Seu perfil foi atualizado com sucesso.
                        </div>
                    </div>
                @endif
            </form>
        </section>
    </div>
</div>

<!-- Modal para cropping da imagem -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Recortar Imagem</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="col-md-8">
                            <img src="" id="sample_image" style="max-width: 100%;">
                        </div>
                        <div class="col-md-4">
                            <div class="preview" style="width: 200px; height: 200px; overflow: hidden; border: 1px solid #ddd; border-radius: 50%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="crop" class="btn btn-primary">Recortar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script>
// Função para aplicar máscara de CPF
function applyCPFMask() {
    const cpfInput = document.getElementById('cpf');
    if (cpfInput) {
        // Aplicar máscara no valor existente ao carregar a página
        if (cpfInput.value) {
            let value = cpfInput.value.replace(/\D/g, '');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            cpfInput.value = value;
        }
        
        // Aplicar máscara durante a digitação
        cpfInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            e.target.value = value;
            
            // Validar CPF em tempo real
            if (value.length === 14) {
                validateCPF(value);
            }
        });
    }
}

// Função para aplicar máscara de telefone
function applyPhoneMask() {
    const phoneFields = ['telefone_celular', 'telefone_residencial', 'telefone_comercial'];
    
    phoneFields.forEach(fieldId => {
        const phoneInput = document.getElementById(fieldId);
        if (phoneInput) {
            // Aplicar máscara no valor existente ao carregar a página
            if (phoneInput.value) {
                let value = phoneInput.value.replace(/\D/g, '');
                if (value.length <= 10) {
                    value = value.replace(/(\d{2})(\d)/, '($1) $2');
                    value = value.replace(/(\d{4})(\d)/, '$1-$2');
                } else {
                    value = value.replace(/(\d{2})(\d)/, '($1) $2');
                    value = value.replace(/(\d{5})(\d)/, '$1-$2');
                }
                phoneInput.value = value;
            }
            
            // Aplicar máscara durante a digitação
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

// Função para aplicar máscara de CEP e buscar endereço
function applyCEPMask() {
    const cepInput = document.getElementById('cep');
    if (cepInput) {
        // Aplicar máscara no valor existente ao carregar a página
        if (cepInput.value) {
            let value = cepInput.value.replace(/\D/g, '');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            cepInput.value = value;
        }
        
        // Aplicar máscara durante a digitação
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
            
            // Buscar endereço quando CEP estiver completo
            if (value.length === 9) {
                fetchAddress(value);
            }
        });
    }
}

// Função para buscar endereço pelo CEP
function fetchAddress(cep) {
    const cleanCep = cep.replace(/\D/g, '');
    
    if (cleanCep.length === 8) {
        fetch(`https://viacep.com.br/ws/${cleanCep}/json/`)
            .then(response => response.json())
            .then(data => {
                if (data.erro) {
                    showCepError('CEP não encontrado');
                    return;
                }
                
                fillAddressFields(data);
            })
            .catch(error => {
                console.error('Erro ao buscar CEP:', error);
                showCepError('Erro ao buscar CEP');
            });
    }
}

// Função para preencher os campos de endereço
function fillAddressFields(data) {
    const fields = {
        'endereco': data.logradouro,
        'bairro': data.bairro,
        'cidade': data.localidade,
        'estado': data.uf
    };
    
    Object.keys(fields).forEach(field => {
        const element = document.getElementById(field);
        if (element && fields[field]) {
            element.value = fields[field];
        }
    });
}

// Função para validar CPF
function validateCPF(cpf) {
    const cleanCpf = cpf.replace(/\D/g, '');
    
    if (cleanCpf.length !== 11) return false;
    
    // Verifica se todos os dígitos são iguais
    if (/^(\d)\1{10}$/.test(cleanCpf)) return false;
    
    let sum = 0;
    let remainder;
    
    // Validação do primeiro dígito
    for (let i = 1; i <= 9; i++) {
        sum += parseInt(cleanCpf.substring(i-1, i)) * (11 - i);
    }
    
    remainder = (sum * 10) % 11;
    
    if ((remainder === 10) || (remainder === 11)) remainder = 0;
    if (remainder !== parseInt(cleanCpf.substring(9, 10))) return false;
    
    sum = 0;
    
    // Validação do segundo dígito
    for (let i = 1; i <= 10; i++) {
        sum += parseInt(cleanCpf.substring(i-1, i)) * (12 - i);
    }
    
    remainder = (sum * 10) % 11;
    
    if ((remainder === 10) || (remainder === 11)) remainder = 0;
    if (remainder !== parseInt(cleanCpf.substring(10, 11))) return false;
    
    return true;
}

function showCepError(message) {
    const errorEl = document.getElementById('cep-error');
    if (errorEl) {
        errorEl.textContent = message;
        errorEl.style.display = 'block';
        setTimeout(() => {
            errorEl.style.display = 'none';
        }, 3000);
    }
}

// Aplica as máscaras quando a página carrega
document.addEventListener('DOMContentLoaded', function() {
    applyCPFMask();
    applyPhoneMask();
    applyCEPMask();
});

// Script para cropping de imagem
$(document).ready(function(){
    var $modal = $('#modal');
    var image = document.getElementById('sample_image');
    var cropper;
    
    $('.image').change(function(event){
        var files = event.target.files;
        var done = function (url) {
            image.src = url;
            $modal.modal('show');
        };
        var reader;
        var file;
        var url;
        
        if (files && files.length > 0) {
            file = files[0];
            
            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function (e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });
    
    $modal.on('shown.bs.modal', function () {
        cropper = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 3,
            preview: '.preview'
        });
    }).on('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
    });
    
    $("#crop").click(function(){
        canvas = cropper.getCroppedCanvas({
            width: 400,
            height: 400,
        });
        
        canvas.toBlob(function(blob){
            url = URL.createObjectURL(blob);
            var reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function(){
                var base64data = reader.result;
                $('#cropped_image').val(base64data);
                $('#croppedImage').attr('src', base64data);
                $('#croppedImageContainer').show();
                $modal.modal('hide');
            }
        });
    });
});
</script>
@endsection