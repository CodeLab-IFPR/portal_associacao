@extends('layouts.admin')

@section('title')
Editar Lançamento
@endsection

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Editar Lançamento de Serviço</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar Lançamento</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container d-flex justify-content-center align-items-center">
    <div class="col-md-6">
        <form action="{{ route('lancamentos.update', $lancamento->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="projeto_id"><strong>Projeto*</strong></label>
                <select name="projeto_id" class="form-control" required>
                    @foreach($projetos as $projeto)
                        <option value="{{ $projeto->id }}" {{ $lancamento->projeto_id == $projeto->id ? 'selected' : '' }}>{{ $projeto->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="servico_id"><strong>Serviço*</strong></label>
                <select name="servico_id" class="form-control" required>
                    @foreach($servicos as $servico)
                        <option value="{{ $servico->id }}" {{ $lancamento->servico_id == $servico->id ? 'selected' : '' }}>{{ $servico->descricao }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="data_inicio"><strong>Data de Início*</strong></label>
                <input type="date" name="data_inicio" class="form-control" value="{{ \Carbon\Carbon::parse($lancamento->data_inicio)->format('Y-m-d') }}" required>
            </div>
            <div class="form-group">
                <label for="data_final"><strong>Data Final*</strong></label>
                <input type="date" name="data_final" class="form-control" value="{{ \Carbon\Carbon::parse($lancamento->data_final)->format('Y-m-d') }}" required>
            </div>
            <div class="form-group">
                <label for="horas_trabalhadas"><strong>Horas Trabalhadas*</strong></label>
                <input type="number" name="horas_trabalhadas" class="form-control" value="{{ $lancamento->horas_trabalhadas }}" required>
            </div>
            <div class="form-group">
                <label for="link"><strong>Link*</strong></label>
                <input type="url" name="link" class="form-control" value="{{ $lancamento->link }}" required>
            </div>
            <div class="form-group">
                <label for="descricao"><strong>Descrição</strong></label>
                <textarea name="descricao" class="form-control" rows="3" style="resize: none;" maxlength="300">{{ $lancamento->descricao }}</textarea>
                @error('descricao')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group mt-4 d-flex justify-content-between">
                <a href="{{ route('lancamentos.index') }}" class="btn btn-outline-danger">Voltar</a>
                <button type="submit" class="btn btn-outline-success">Salvar</button>
            </div>
        </form>
    </div>
</div>
<script>
    document.querySelector('form').addEventListener('submit', function(event) {
        var linkInput = document.querySelector('input[name="link"]');
        if (!linkInput.value.startsWith('https://github.com/')) {
            event.preventDefault();
            alert('Link não permitido!');
        }
    });
</script>
@endsection