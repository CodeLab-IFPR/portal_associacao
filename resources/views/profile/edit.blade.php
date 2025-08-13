@extends('layouts.admin')

@section('title')
Perfil
@endsection

@section('content')
    <div class="py-4 d-flex justify-content-center">
        @if(session('status'))
            <div id="alert" class="alert {{ session('status') === 'password-error' ? 'alert-danger' : 'alert-success' }} alert-dismissible fade show" role="alert">
                <div class="alert-content">
                    @if(session('status') === 'password-error')
                        <strong>{{ __('Erro: Senha atual incorreta ou senhas não coincidem.') }}</strong>
                    @else
                        <strong>{{ __(session('status')) }}</strong>
                    @endif
                </div>
                <div class="progress-bar-container">
                    <div id="progress-bar" class="progress-bar" style="background-color: {{ session('status') === 'password-error' ? 'rgba(220,53,69,0.2)' : 'rgba(0,0,0,0.2)' }};"></div>
                </div>
            </div>
        @endif

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6" style="width: 50%;">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg rounded-4">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>
    </div>

    <!-- Adicionar o CSS e JavaScript para o progress bar -->
    <style>
        .progress-bar-container {
            width: 100%;
            height: 4px;
            position: relative;
            margin-top: 8px;
        }
        .progress-bar {
            height: 100%;
            background-color: rgba(0,0,0,0.2);
            width: 0;
            animation: progress 3s linear forwards;
        }
        @keyframes progress {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>

    <script>
        setTimeout(function() {
            document.getElementById('alert')?.remove();
        }, 3000);
    </script>
@endsection
