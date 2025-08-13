@extends('layouts.admin')

@section('title')
Novo Lançamento de Serviço
@endsection

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Criar Lançamento</h3>
                @if($errors->has('projeto_id'))
                    <span class="text-danger">{{ $errors->first('projeto_id') }}</span>
                @endif
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Novo Lançamento</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container d-flex justify-content-center align-items-center">
    <div class="col-md-6">
        <form action="{{ route('lancamentos.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="projeto_id"><strong>Projeto*</strong></label>
                <select name="projeto_id" class="form-select" required>
                    @foreach($projetos as $projeto)
                        <option value="{{ $projeto->id }}">{{ $projeto->nome }}</option>
                    @endforeach
                </select>
                @error('projeto_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="servico_id"><strong>Serviço*</strong></label>
                <select name="servico_id" class="form-select" required>
                    @foreach($servicos as $servico)
                        <option value="{{ $servico->id }}">{{ $servico->descricao }}</option>
                    @endforeach
                </select>
                @error('servico_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="data_inicio"><strong>Data de Início*</strong></label>
                    <input type="date" name="data_inicio" id="data_inicio" class="form-control" placeholder="dd/mm/yyyy"
                        required>
                    @error('data_inicio')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="data_final"><strong>Data Final*</strong></label>
                    <input type="date" name="data_final" id="data_final" class="form-control" placeholder="dd/mm/yyyy"
                        required>
                    @error('data_final')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

            <div class="form-group">
                <label for="horas_trabalhadas"><strong>Horas Trabalhadas*</strong></label>
                <input type="number" name="horas_trabalhadas" class="form-control" required>
                @error('horas_trabalhadas')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="link"><strong>Link*</strong></label>
                <input type="url" name="link" class="form-control" required>
                @error('link')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="descricao"><strong>Descrição</strong></label>
                <textarea name="descricao" class="form-control" rows="3" style="resize: none;" maxlength="300"></textarea>
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
    document.getElementById('data_inicio').addEventListener('change', function () {
        var dataInicio = this.value;
        document.getElementById('data_final').setAttribute('min', dataInicio);
    });
</script>
@endsection