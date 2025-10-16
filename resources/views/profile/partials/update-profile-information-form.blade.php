<div class="container-fluid container-md pt-2 d-flex justify-content-center">
    <div class="card-body p-2 p-md-4 p-lg-5 rounded-4 shadow-sm bg-white w-100" style="max-width: 900px;">
        <section>
            <!-- Nova seção da imagem no topo -->
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

            <header class="text-center">
                <h2 class="fs-5 fs-md-4 fw-medium mb-4">{{ __('Informações do Perfil') }}</h2>
                <p class="text-muted mb-4">{{ __("Atualize as informações do perfil e endereço de email da sua conta.") }}</p>
            </header>

            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                @csrf
            </form>

            <form method="post" action="{{ route('profile.update') }}" class="mt-6" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <input type="hidden" name="cropped_image" id="cropped_image" value="{{ old('cropped_image') }}">
                
                <!-- Modalidade Principal -->
                <div class="mb-4">
                    <h5 class="text-primary"><strong>Modalidade Principal:</strong></h5>
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
                    @error('modalidade_principal')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Dados do usuário -->
                <div class="mb-4">
                    <h5 class="text-primary"><strong>Dados do usuário:</strong></h5>
                    
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
                                <input type="text" name="sobrenome" class="form-control @error('sobrenome') is-invalid @enderror" id="sobrenome" value="{{ old('sobrenome', $user->sobrenome) }}">
                                @error('sobrenome')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="data_nascimento" class="form-label"><strong>Data de Nascimento:</strong></label>
                                <input type="date" name="data_nascimento" class="form-control @error('data_nascimento') is-invalid @enderror" id="data_nascimento" value="{{ old('data_nascimento', $user->data_nascimento) }}">
                                @error('data_nascimento')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cpf" class="form-label"><strong>CPF:</strong></label>
                                <input type="text" name="cpf" class="form-control @error('cpf') is-invalid @enderror" id="cpf" value="{{ old('cpf', $user->cpf) }}" maxlength="14">
                                @error('cpf')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="rg" class="form-label"><strong>RG:</strong></label>
                                <input type="text" name="rg" class="form-control @error('rg') is-invalid @enderror" id="rg" value="{{ old('rg', $user->rg) }}">
                                @error('rg')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cargo" class="form-label"><strong>Cargo:</strong></label>
                                <input type="text" name="cargo" class="form-control @error('cargo') is-invalid @enderror" id="cargo" value="{{ old('cargo', $user->cargo) }}">
                                @error('cargo')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contato -->
                <div class="mb-4">
                    <h5 class="text-primary"><strong>Contato:</strong></h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefone_celular" class="form-label"><strong>Telefone Celular:</strong></label>
                                <input type="tel" name="telefone_celular" class="form-control @error('telefone_celular') is-invalid @enderror" id="telefone_celular" value="{{ old('telefone_celular', $user->telefone_celular) }}" maxlength="15">
                                @error('telefone_celular')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="celular_whatsapp" name="celular_whatsapp" value="1" {{ old('celular_whatsapp', $user->celular_whatsapp) ? 'checked' : '' }}>
                                <label class="form-check-label" for="celular_whatsapp">
                                    <strong>Celular com WhatsApp?</strong>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefone_residencial" class="form-label"><strong>Telefone Residencial:</strong></label>
                                <input type="tel" name="telefone_residencial" class="form-control @error('telefone_residencial') is-invalid @enderror" id="telefone_residencial" value="{{ old('telefone_residencial', $user->telefone_residencial) }}" maxlength="15">
                                @error('telefone_residencial')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefone_comercial" class="form-label"><strong>Telefone Comercial:</strong></label>
                                <input type="tel" name="telefone_comercial" class="form-control @error('telefone_comercial') is-invalid @enderror" id="telefone_comercial" value="{{ old('telefone_comercial', $user->telefone_comercial) }}" maxlength="15">
                                @error('telefone_comercial')
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
                                    <div>
                                        <br>
                                        <small class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                                            {{ __('Your email address is unverified.') }}
                                            &nbsp;
                                            <button form="send-verification" class="btn btn-outline-dark btn-sm">
                                                {{ __('Click here to re-send the verification email.') }}
                                            </button>
                                        </small>

                                        @if (session('status') === 'verification-link-sent')
                                            <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                                {{ __('A new verification link has been sent to your email address.') }}
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email_alternativo" class="form-label"><strong>Email Alternativo:</strong></label>
                                <input type="email" name="email_alternativo" class="form-control @error('email_alternativo') is-invalid @enderror" id="email_alternativo" value="{{ old('email_alternativo', $user->email_alternativo) }}">
                                @error('email_alternativo')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Endereço -->
                <div class="mb-4">
                    <h5 class="text-primary"><strong>Endereço:</strong></h5>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="cep" class="form-label"><strong>CEP:</strong></label>
                                <input type="text" name="cep" class="form-control @error('cep') is-invalid @enderror" id="cep" value="{{ old('cep', $user->cep) }}" maxlength="10">
                                <div id="cep-loading" style="display: none;">
                                    <small class="text-info">🔍 Buscando endereço...</small>
                                </div>
                                <div id="cep-error" class="form-text text-danger" style="display: none;"></div>
                                @error('cep')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="logradouro" class="form-label"><strong>Logradouro:</strong></label>
                                <input type="text" name="logradouro" class="form-control @error('logradouro') is-invalid @enderror" id="logradouro" value="{{ old('logradouro', $user->logradouro) }}">
                                @error('logradouro')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="numero" class="form-label"><strong>Número:</strong></label>
                                <input type="text" name="numero" class="form-control @error('numero') is-invalid @enderror" id="numero" value="{{ old('numero', $user->numero) }}">
                                @error('numero')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="bairro" class="form-label"><strong>Bairro:</strong></label>
                                <input type="text" name="bairro" class="form-control @error('bairro') is-invalid @enderror" id="bairro" value="{{ old('bairro', $user->bairro) }}">
                                @error('bairro')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estado" class="form-label"><strong>Estado:</strong></label>
                                <input type="text" name="estado" class="form-control @error('estado') is-invalid @enderror" id="estado" value="{{ old('estado', $user->estado) }}">
                                @error('estado')
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
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-outline-success btn-flat">
                        <i class="fas fa-plus"></i> {{ __('Save') }}
                    </button>

                    @if (session('status') === 'profile-updated')
                        <p x-data="{ show: true }"
                           x-show="show"
                           x-transition
                           x-init="setTimeout(() => show = false, 2000)"
                           class="text-success">{{ __('Saved.') }}</p>
                    @endif
                </div>
            </form>
        </section>
    </div>
</div>

<!-- Modal para Cropper.js -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Recortar Imagem</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="img-container" style="max-width: 100%; margin-top: 20px;">
                            <img id="image" src="" alt="Imagem para recortar" style="max-width: 100%;">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="preview" style="width: 140px; height: 140px; border: 1px solid #ddd; border-radius: 50%; overflow: hidden;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="cancel-button">Cancelar</button>
                <button type="button" class="btn btn-primary" id="crop">Recortar</button>
            </div>
        </div>
    </div>
</div>

@vite('resources/js/utils/viacep.js')

<!-- Scripts necessários para Cropper.js -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>

<script>
// Manter o modal e a lógica de exibição da imagem ao recortar
var $modal = $('#modal');
var image = document.getElementById('image');
var cropper;

$("body").on("change", ".image", function(e){
    var files = e.target.files;
    var done = function (url) {
        image.src = url;
        $modal.modal('show');
    };

    var reader;
    var file;

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
    var canvas = cropper.getCroppedCanvas({
        width: 160,
        height: 160,
    });

    // Criando o canvas circular
    var circleCanvas = document.createElement('canvas');
    var circleCtx = circleCanvas.getContext('2d');
    circleCanvas.width = 160;
    circleCanvas.height = 160;

    circleCtx.beginPath();
    circleCtx.arc(80, 80, 80, 0, 2 * Math.PI);
    circleCtx.closePath();
    circleCtx.clip();

    circleCtx.drawImage(canvas, 0, 0, 160, 160);

    circleCanvas.toBlob(function(blob) {
        var url = URL.createObjectURL(blob);
        var reader = new FileReader();
        reader.readAsDataURL(blob);
        reader.onloadend = function() {
            var base64data = reader.result; 
            $('#cropped_image').val(base64data);
            $('#croppedImage').attr('src', base64data); // Atualizar o src da imagem do preview
            $('#croppedImagePreview').show(); // Mostrar o preview da imagem
            $modal.modal('hide');
        };
    });
});

@if(old('cropped_image'))
    $(document).ready(function() {
        $('#croppedImageContainer').show();
    });
@endif

// Função para validar CPF
function validateCPF(cpf) {
    // Remove pontos e hífen
    cpf = cpf.replace(/[^\d]+/g, '');
    
    // Verifica se tem 11 dígitos
    if (cpf.length !== 11) return false;
    
    // Verifica se todos os dígitos são iguais (ex: 111.111.111-11)
    if (/^(\d)\1{10}$/.test(cpf)) return false;
    
    // Calcula o primeiro dígito verificador
    let sum = 0;
    for (let i = 0; i < 9; i++) {
        sum += parseInt(cpf.charAt(i)) * (10 - i);
    }
    let remainder = 11 - (sum % 11);
    let digit1 = remainder >= 10 ? 0 : remainder;
    
    // Verifica o primeiro dígito
    if (digit1 !== parseInt(cpf.charAt(9))) return false;
    
    // Calcula o segundo dígito verificador
    sum = 0;
    for (let i = 0; i < 10; i++) {
        sum += parseInt(cpf.charAt(i)) * (11 - i);
    }
    remainder = 11 - (sum % 11);
    let digit2 = remainder >= 10 ? 0 : remainder;
    
    // Verifica o segundo dígito
    return digit2 === parseInt(cpf.charAt(10));
}

// Função simples para máscara CPF
function applyCPFMask() {
    const cpfField = document.getElementById('cpf');
    if (cpfField) {
        console.log('Aplicando máscara CPF...'); // Debug
        
        // Criar elemento para mostrar erro de validação
        let errorElement = document.getElementById('cpf-error');
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.id = 'cpf-error';
            errorElement.className = 'form-text text-danger';
            errorElement.style.display = 'none';
            cpfField.parentNode.appendChild(errorElement);
        }
        
        cpfField.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove tudo que não é dígito
            console.log('Valor digitado:', value); // Debug
            
            // Aplica a máscara 000.000.000-00
            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                e.target.value = value;
                console.log('Valor formatado:', value); // Debug
            }
        });
        
        // Validação quando o campo perde o foco
        cpfField.addEventListener('blur', function(e) {
            const cpfValue = e.target.value;
            if (cpfValue && cpfValue.length >= 14) { // CPF completo tem 14 caracteres com máscara
                if (validateCPF(cpfValue)) {
                    // CPF válido
                    e.target.classList.remove('is-invalid');
                    e.target.classList.add('is-valid');
                    errorElement.style.display = 'none';
                } else {
                    // CPF inválido
                    e.target.classList.remove('is-valid');
                    e.target.classList.add('is-invalid');
                    errorElement.textContent = 'CPF inválido. Verifique os números digitados.';
                    errorElement.style.display = 'block';
                }
            } else if (cpfValue) {
                // CPF incompleto
                e.target.classList.remove('is-valid');
                e.target.classList.add('is-invalid');
                errorElement.textContent = 'CPF incompleto. Digite todos os 11 dígitos.';
                errorElement.style.display = 'block';
            } else {
                // Campo vazio - remove validação visual
                e.target.classList.remove('is-invalid', 'is-valid');
                errorElement.style.display = 'none';
            }
        });
        
        // Permite apenas números
        cpfField.addEventListener('keypress', function(e) {
            // Permite apenas números e teclas de controle
            if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                e.preventDefault();
            }
        });
        
        console.log('Máscara CPF aplicada com sucesso!'); // Debug
    } else {
        console.log('Campo CPF não encontrado!'); // Debug
    }
}

// Função para máscara de telefone
function applyPhoneMask() {
    const phoneFields = ['telefone_celular', 'telefone_residencial', 'telefone_comercial'];
    
    phoneFields.forEach(fieldId => {
        const phoneField = document.getElementById(fieldId);
        if (phoneField) {
            console.log('Aplicando máscara telefone em:', fieldId);
            
            phoneField.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, ''); // Remove tudo que não é dígito
                
                // Aplica máscara (XX) XXXXX-XXXX ou (XX) XXXX-XXXX
                if (value.length <= 11) {
                    if (value.length <= 2) {
                        value = value.replace(/(\d{1,2})/, '($1');
                    } else if (value.length <= 6) {
                        value = value.replace(/(\d{2})(\d{1,4})/, '($1) $2');
                    } else if (value.length <= 10) {
                        value = value.replace(/(\d{2})(\d{4})(\d{1,4})/, '($1) $2-$3');
                    } else {
                        value = value.replace(/(\d{2})(\d{5})(\d{1,4})/, '($1) $2-$3');
                    }
                    e.target.value = value;
                }
            });
            
            // Permite apenas números
            phoneField.addEventListener('keypress', function(e) {
                if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                    e.preventDefault();
                }
            });
        }
    });
}

// Função para máscara de CEP
function applyCEPMask() {
    const cepField = document.getElementById('cep');
    if (cepField) {
        console.log('Aplicando máscara CEP...');
        let hasEdited = false;
        
        cepField.addEventListener('input', function(e) {
            hasEdited = true;
            let value = e.target.value.replace(/\D/g, ''); // Remove tudo que não é dígito
            
            // Aplica a máscara 00000-000
            if (value.length <= 8) {
                value = value.replace(/(\d{5})(\d{1,3})/, '$1-$2');
                e.target.value = value;
            }
        });
        
        // Buscar endereço quando sair do campo
        cepField.addEventListener('blur', function() {
            if (!hasEdited) return;
            const cep = cepField.value;
            hasEdited = false;
            
            if (cep.length !== 9) {
                return;
            }
            
            showCepLoading();
            
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    hideCepLoading();
                    
                    if (data.erro) {
                        showCepError('CEP não encontrado');
                        fillAddressFields('');
                        return;
                    }
                    
                    fillAddressFields(data);
                })
                .catch(error => {
                    hideCepLoading();
                    showCepError('Erro ao buscar CEP');
                    fillAddressFields('');
                    console.error('Erro:', error);
                });
        });
        
        // Permite apenas números
        cepField.addEventListener('keypress', function(e) {
            if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                e.preventDefault();
            }
        });
        
        console.log('Máscara CEP aplicada com sucesso!');
    }
}

// Funções auxiliares para CEP
function fillAddressFields(data) {
    const fields = {
        'logradouro': data.logradouro || '',
        'bairro': data.bairro || '',
        'cidade': data.localidade || '',
        'estado': data.uf || ''
    };
    
    Object.keys(fields).forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (field) {
            field.value = fields[fieldName];
            field.readOnly = false;
        }
    });
}

function showCepLoading() {
    const loadingEl = document.getElementById('cep-loading');
    if (loadingEl) {
        loadingEl.style.display = 'block';
    }
}

function hideCepLoading() {
    const loadingEl = document.getElementById('cep-loading');
    if (loadingEl) {
        loadingEl.style.display = 'none';
    }
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
    console.log('DOM carregado, aplicando máscaras...'); // Debug
    applyCPFMask();
    applyPhoneMask();
    applyCEPMask();
});
</script>

<style>
/* Melhorias responsivas para mobile */
@media (max-width: 576px) {
    .card-body {
        border-radius: 0.5rem !important;
    }
    
    .mb-3 {
        margin-bottom: 0.75rem !important;
    }
    
    .form-control {
        font-size: 16px !important; /* Evita zoom no iOS */
    }
    
    .btn {
        font-size: 0.9rem !important;
        padding: 0.5rem 1rem !important;
    }
    
    .container {
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }
}

@media (max-width: 768px) {
    .col-md-6 {
        margin-bottom: 0.5rem;
    }
}
</style>
