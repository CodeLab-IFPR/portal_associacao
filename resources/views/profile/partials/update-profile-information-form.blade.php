<div class="container pt-2 d-flex justify-content-center">
    <div class="card-body p-4 p-lg-5 rounded-4 shadow-sm bg-white">
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
                <h2 class="fs-4 fw-medium mb-4">{{ __('Profile Information') }}</h2>
                <p class="text-muted mb-4">{{ __("Update your account's profile information and email address.") }}</p>
            </header>

            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                @csrf
            </form>

            <form method="post" action="{{ route('profile.update') }}" class="mt-6" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <input type="hidden" name="cropped_image" id="cropped_image" value="{{ old('cropped_image') }}">
                <div class="mb-3">
                    <label for="name" class="form-label"><strong>{{ __('Name') }}:</strong></label>
                    <input type="text" 
                        name="name" 
                        class="form-control form-control @error('name') is-invalid @enderror" 
                        id="name"
                        value="{{ old('name', $user->name) }}" 
                        required 
                        autofocus>
                    @error('name')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="cargo" class="form-label"><strong>{{ __('Cargo') }}:</strong></label>
                    <input type="text" name="cargo" class="form-control form-control @error('cargo') is-invalid @enderror" 
                        id="cargo" value="{{ old('cargo', $user->cargo) }}" placeholder="Cargo...">
                    @error('cargo')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="cpf" class="form-label"><strong>{{ __('CPF') }}:</strong></label>
                    <input type="text" name="cpf" class="form-control form-control @error('cpf') is-invalid @enderror" 
                        id="cpf" value="{{ old('cpf', $user->cpf) }}" placeholder="CPF...">
                    @error('cpf')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label"><strong>{{ __('Email') }}:</strong></label>
                    <input type="email" 
                        name="email" 
                        class="form-control form-control @error('email') is-invalid @enderror" 
                        id="email"
                        value="{{ old('email', $user->email) }}" 
                        required>
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

                <div class="mb-3">
                    <label for="biografia" class="form-label"><strong>{{ __('Biografia') }}:</strong></label>
                    <textarea class="form-control form-control @error('biografia') is-invalid @enderror" style="height:150px"
                        name="biografia" id="biografia" placeholder="Biografia...">{{ old('biografia', $user->biografia) }}</textarea>
                    @error('biografia')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="linkedin" class="form-label"><strong>{{ __('LinkedIn') }}:</strong></label>
                    <input type="url" name="linkedin" class="form-control form-control @error('linkedin') is-invalid @enderror"
                        id="linkedin" value="{{ old('linkedin', $user->linkedin) }}" placeholder="LinkedIn URL">
                    @error('linkedin')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="github" class="form-label"><strong>{{ __('GitHub') }}:</strong></label>
                    <input type="url" name="github" class="form-control form-control @error('github') is-invalid @enderror"
                        id="github" value="{{ old('github', $user->github) }}" placeholder="GitHub URL">
                    @error('github')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="alt" class="form-label"><strong>{{ __('Alt') }}:</strong></label>
                    <input type="text" name="alt" class="form-control form-control @error('alt') is-invalid @enderror"
                        id="alt" value="{{ old('alt', $user->alt) }}" placeholder="Texto alternativo...">
                    @error('alt')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
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

        <!-- Início da seção de senha -->
        <section class="mt-5 pt-5 border-top">
            <header>
                <h2 class="fs-4 fw-medium mb-4">{{ __('Update Password') }}</h2>
                <p class="text-muted mb-4">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
            </header>

            <form method="post" action="{{ route('password.update') }}" class="mt-6">
                @csrf
                @method('put')

                <div class="mb-3">
                    <label for="update_password_current_password" class="form-label"><strong>{{ __('Current Password') }}:</strong></label>
                    <input type="password" 
                        name="current_password" 
                        class="form-control form-control @error('current_password') is-invalid @enderror" 
                        id="update_password_current_password"
                        required>
                    @error('current_password')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="update_password_password" class="form-label"><strong>{{ __('New Password') }}:</strong></label>
                    <input type="password" 
                        name="password" 
                        class="form-control form-control @error('password') is-invalid @enderror" 
                        id="update_password_password"
                        required>
                    @error('password')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="update_password_password_confirmation" class="form-label"><strong>{{ __('Confirm Password') }}:</strong></label>
                    <input type="password" 
                        name="password_confirmation" 
                        class="form-control form-control @error('password_confirmation') is-invalid @enderror" 
                        id="update_password_password_confirmation"
                        required>
                    @error('password_confirmation')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-outline-success btn-flat">
                        <i class="fas fa-plus"></i> {{ __('Save') }}
                    </button>

                    @if (session('status') === 'password-updated')
                        <p x-data="{ show: true }"
                           x-show="show"
                           x-transition
                           x-init="setTimeout(() => show = false, 2000)"
                           class="text-success">{{ __('Saved.') }}</p>
                    @elseif (session('status') === 'password-error')
                        <p x-data="{ show: true }"
                           x-show="show"
                           x-transition
                           x-init="setTimeout(() => show = false, 3000)"
                           class="text-danger">{{ __('Erro: Senha atual incorreta ou senhas não coincidem.') }}</p>
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
</script>