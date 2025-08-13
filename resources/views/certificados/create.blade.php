@extends('layouts.admin')

@section('title')
Certificados
@endsection

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Certificado - Cadastro</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Certificado - Cadastro
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container d-flex justify-content-center">
    <div class="card-body" style="max-width: 600px;">
        @if(!empty($certificadosData))
            <form method="POST" action="{{ route('certificados.store') }}">
                @csrf
                @foreach($certificadosData as $index => $data)
                    <div class="mb-3">
                        <label for="users_id_{{ $index }}" class="form-label"><strong>Membro*</strong></label>
                        <select id="users_id_{{ $index }}" name="certificados[{{ $index }}][user_id]" class="form-select" required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $user->id == $data['user_id'] ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="horas_{{ $index }}" class="form-label"><strong>Horas:*</strong></label>
                        <input type="text" class="form-control" id="horas_{{ $index }}" name="certificados[{{ $index }}][horas]" value="{{ $data['horas'] }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="data_{{ $index }}" class="form-label"><strong>Data:*</strong></label>
                        <input type="date" class="form-control" id="data_{{ $index }}" name="certificados[{{ $index }}][data]" value="{{ $data['data'] }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="descricao_{{ $index }}" class="form-label"><strong>Descrição</strong></label>
                        <textarea class="form-control" id="descricao_{{ $index }}" name="certificados[{{ $index }}][descricao]" required>{{ $data['descricao'] }}</textarea>
                    </div>

                    <input type="hidden" name="certificados[{{ $index }}][servico_id]" value="{{ $data['servico_id'] }}">
                @endforeach

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-outline-success">
                        <i class="fas fa-plus"></i> Criar Certificado
                    </button>
                </div>
            </form>
        @else
            <!-- Formulário para criação manual de certificados -->
            <form method="POST" action="{{ route('certificados.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="manual_user_id" class="form-label"><strong>Membro*</strong></label>
                    <select id="manual_user_id" name="manual_certificado[user_id]" class="form-select" required>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="manual_horas" class="form-label"><strong>Horas:*</strong></label>
                    <input type="text" class="form-control" id="manual_horas" name="manual_certificado[horas]" required>
                </div>

                <div class="mb-3">
                    <label for="manual_data" class="form-label"><strong>Data:*</strong></label>
                    <input type="date" class="form-control" id="manual_data" name="manual_certificado[data]" required>
                </div>

                <div class="mb-3">
                    <label for="manual_descricao" class="form-label"><strong>Descrição</strong></label>
                    <textarea class="form-control" id="manual_descricao" name="manual_certificado[descricao]" required></textarea>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-outline-success">
                        <i class="fas fa-plus"></i> Criar Certificado
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection