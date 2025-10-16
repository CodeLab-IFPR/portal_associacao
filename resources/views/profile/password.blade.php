@extends('layouts.admin')

@section('title', 'Alterar Senha')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Alterar Senha</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Alterar Senha</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container pt-2 d-flex justify-content-center">
    <div class="card-body p-4 p-lg-5 rounded-4 shadow-sm bg-white" style="max-width: 900px; width: 100%;">
        <section >
            <header class="text-center mb-4">
                <h2 class="fs-4 fw-medium mb-3">{{ __('Alterar Senha') }}</h2>
                <p class="text-muted">{{ __('Certifique-se de que sua conta esteja usando uma senha longa e aleatória para permanecer segura.') }}</p>
            </header>

            <form method="post" action="{{ route('password.custom.update') }}" class="mt-4">
                @csrf
                @method('put')

                <div class="mb-3">
                    <label for="current_password" class="form-label"><strong>{{ __('Senha Atual') }}:</strong></label>
                    <input type="password" 
                        name="current_password" 
                        class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                        id="current_password"
                        placeholder="Digite sua senha atual"
                        required>
                    @error('current_password', 'updatePassword')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label"><strong>{{ __('Nova Senha') }}:</strong></label>
                    <input type="password" 
                        name="password" 
                        class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                        id="password"
                        placeholder="Digite sua nova senha"
                        required>
                    @error('password', 'updatePassword')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                    <div class="form-text text-muted">
                        <small>A senha deve ter pelo menos 8 caracteres.</small>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label"><strong>{{ __('Confirmar Nova Senha') }}:</strong></label>
                    <input type="password" 
                        name="password_confirmation" 
                        class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                        id="password_confirmation"
                        placeholder="Confirme sua nova senha"
                        required>
                    @error('password_confirmation', 'updatePassword')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                    <button type="submit" class="btn btn-outline-success">
                        <i class="fas fa-key"></i> {{ __('Alterar Senha') }}
                    </button>
                </div>

                @if (session('status') === 'password-updated')
                    <div class="alert alert-success mt-3 d-flex align-items-center" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <div>
                            <strong>Sucesso!</strong> Sua senha foi alterada com sucesso.
                        </div>
                    </div>
                @endif
            </form>
        </section>
    </div>
</div>
@endsection