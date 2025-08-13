@extends('layouts.admin')

<!-- Título -->
@section('title')
Visualizar Submissão
@endsection
<!-- Título -->

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Visualizar Submissão</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('submissions.index') }}">Submissões</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Visualizar Submissão</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">{{ $submission->name }}</h4>
        </div>
        <div class="card-body">
            <div class="form-group">
                <strong>Nome:</strong>
                <p>{{ $submission->name }}</p>
            </div>
            <div class="form-group">
                <strong>Email:</strong>
                <p>{{ $submission->email }}</p>
            </div>
            <div class="form-group">
                <strong>Descrição da Demanda:</strong>
                <p>{{ $submission->demand_description }}</p>
            </div>
            <div class="form-group">
                <strong>Utilidade Esperada:</strong>
                <p>{{ $submission->expected_utility }}</p>
            </div>
            <div class="form-group">
                <strong>Status:</strong>
                <p>{{ $submission->read ? 'Lida' : 'Não Lida' }}</p>
            </div>
            <div class="form-group">
                <strong>Anexos:</strong>
                @if($submission->attachments)
                    <ul>
                        @foreach(json_decode($submission->attachments) as $attachment)
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
