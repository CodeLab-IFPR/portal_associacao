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
<div class="container d-flex justify-content-center">
    <div class="card-body" style="max-width: 600px;">
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="inputNome" class="form-label"><strong>*Nome:</strong></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="inputNome"
                    placeholder="Nome..." value="{{ old('name') }}" required>
                @error('name')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="inputEmail" class="form-label"><strong>*Email:</strong></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="inputEmail"
                    placeholder="Email..." value="{{ old('email') }}" required>
                @error('email')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="inputCargo" class="form-label"><strong>Cargo:</strong></label>
                <input type="text" class="form-control @error('cargo') is-invalid @enderror" name="cargo" id="inputCargo"
                    placeholder="Cargo..." value="{{ old('cargo') }}">
                @error('cargo')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="inputCpf" class="form-label"><strong>*CPF:</strong></label>
                <input type="text" class="form-control @error('cpf') is-invalid @enderror" name="cpf" id="inputCpf"
                    placeholder="CPF..." value="{{ old('cpf') }}" required>
                @error('cpf')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="inputAtivo" class="form-label"><strong>Ativo:</strong></label>
                <input type="checkbox" name="ativo" class="form-check-input @error('ativo') is-invalid @enderror" id="inputAtivo" value="1" {{ old('ativo') ? 'checked' : '' }}>
                @error('ativo')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="inputBiografia" class="form-label"><strong>Biografia:</strong></label>
                <textarea class="form-control @error('biografia') is-invalid @enderror" style="height:150px"
                    name="biografia" id="inputBiografia" placeholder="Biografia...">{{ old('biografia') }}</textarea>
                @error('biografia')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>        
            <div class="mb-3">
                <label for="inputLinkedin" class="form-label"><strong>LinkedIn:</strong></label>
                <input type="url" class="form-control @error('linkedin') is-invalid @enderror" name="linkedin" id="inputLinkedin"
                    placeholder="LinkedIn URL" value="{{ old('linkedin') }}">
                @error('linkedin')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="inputGithub" class="form-label"><strong>GitHub:</strong></label>
                <input type="url" class="form-control @error('github') is-invalid @enderror" name="github" id="inputGithub"
                    placeholder="GitHub URL" value="{{ old('github') }}">
                @error('github')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
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

            <div class="mb-3">
                <label for="inputAlt" class="form-label"><strong>Alt:</strong></label>
                <input type="text" class="form-control @error('alt') is-invalid @enderror" name="alt" id="inputAlt"
                    placeholder="Texto alternativo..." value="{{ old('alt') }}">
                @error('alt')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div id="additional-links"></div>

            <div class="mb-3">
                <label for="inputImagem" class="form-label"><strong>Imagem:</strong></label>
                <input type="file" name="imagem" class="form-control @error('imagem') is-invalid @enderror image" id="inputImagem">
                @error('imagem')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
                <input type="hidden" name="cropped_image" id="cropped_image" value="{{ old('cropped_image') }}">
            </div>

            <div class="mb-3" id="croppedImageContainer" style="{{ old('cropped_image') ? '' : 'display: none;' }}">
                <label for="croppedImagePreview" class="form-label"><strong>Preview da Imagem:</strong></label>
                <div id="croppedImagePreview" style="width: 160px; height: 160px; border: 1px solid #ddd; border-radius: 50%; overflow: hidden;">
                    <img id="croppedImage" src="{{ old('cropped_image') }}" alt="Imagem recortada" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            </div>

            <input type="hidden" name="generated_password" id="generated_password" value="">

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-outline-success">
                    <i class="fas fa-plus"></i> Salvar
                </button>
            </div>
        </form>
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
            $('#croppedImage').attr('src', base64data);
            $('#croppedImagePreview').show();
            $modal.modal('hide');
        };
    });
});

@if(old('cropped_image'))
    $(document).ready(function() {
        $('#croppedImageContainer').show();
    });
@endif

document.addEventListener('DOMContentLoaded', function() {
    var password = Math.random().toString(36).slice(-8);
    document.getElementById('generated_password').value = password;
});

</script>
@endsection