@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Detalhes do Serviço</h1>
        <p><strong>Descrição:</strong> {{ $servico->descricao }}</p>
        <a href="{{ route('servicos.index') }}" class="btn btn-secondary">Voltar</a>
    </div>
@endsection