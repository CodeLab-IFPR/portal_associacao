@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Detalhes do Lançamento de Serviço</h1>
    <div class="card">
        <div class="card-header">
            Lançamento #{{ $lancamento->id }}
        </div>
        <div class="card-body">
            <p><strong>Projeto:</strong> {{ $lancamento->projeto->nome }}</p>
            <p><strong>Serviço:</strong> {{ $lancamento->servico->descricao }}</p>
            <p><strong>Data Início:</strong> {{ $lancamento->data_inicio }}</p>
            <p><strong>Data Final:</strong> {{ $lancamento->data_final }}</p>
            <p><strong>Horas Trabalhadas:</strong> {{ $lancamento->horas_trabalhadas }}</p>
            <p><strong>Link:</strong> <a href="{{ $lancamento->link }}" target="_blank">Ver Link</a></p>
        </div>
    </div>
    <a href="{{ route('lancamentos.index') }}" class="btn btn-secondary mt-3">Voltar</a>
</div>
@endsection