@extends('layouts.admin')

<!-- Título -->
@section('title')
Visualizar Mensagem
@endsection
<!-- Título -->

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Visualizar Mensagem</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('mensagens.index') }}">Mensagens de Contato</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Visualizar Mensagem</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">{{ $mensagem->name }}</h4>
        </div>
        <div class="card-body">
            @if(!empty($mensagem->subject))
                <div class="form-group">
                    <strong>Assunto:</strong>
                    <p>{{ $mensagem->subject }}</p>
                </div>
            @endif
            <div class="form-group">
                <strong>Nome:</strong>
                <p>{{ $mensagem->name }}</p>
            </div>
            <div class="form-group">
                <strong>Email:</strong>
                <p>{{ $mensagem->email }}</p>
            </div>
            <div class="form-group">
                <strong>Mensagem:</strong>
                <p>{{ $mensagem->message }}</p>
            </div>
            <div class="form-group">
                <strong>Status:</strong>
                <p>{{ $mensagem->read ? 'Lida' : 'Não Lida' }}</p>
            </div>
            <div class="form-group">
                <strong>Anexos:</strong>
                @if($mensagem->attachments)
                    <ul>
                        @foreach(json_decode($mensagem->attachments) as $attachment)
                            <li><a href="{{ asset($attachment) }}" target="_blank">{{ basename($attachment) }}</a></li>
                        @endforeach
                    </ul>
                @else
                    <p>Não há anexos.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection