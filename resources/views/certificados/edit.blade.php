@extends('layouts.admin')

<!-- Titulo -->
@section('title')
Certificado - Edição
@endsection
<!-- Titulo -->

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Certificado - Edição</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Certificado - Edição
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container d-flex justify-content-center">
    <div class="card-body" style="max-width: 600px;">
        <form action="{{ route('certificados.update', $certificado->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="user_id" class="form-label"><strong>Membro*</strong></label>
                <select id="user_id" name="user_id" class="form-select" required>
                    <option value="" disabled
                        {{ empty($selectedUserId) ? 'selected' : '' }}
                        class="text-disable">Selecione</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}"
                            {{ $selectedUserId = $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('users_id')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label"><strong>Descrição</strong></label>
                <textarea class="form-control @error('descricao') inválido @enderror" id="descricao" name="descricao"
                    required placeholder="Descrição..." minlength="20" maxlength="520" rows="4"
                    oninput="updateCharacterCount()">{{ old('descricao', $certificado->descricao) }}</textarea>
                <div id="charCount">0/520</div>
                @error('descricao')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="horas" class="form-label"><strong>Horas:*</strong></label>
                <input type="text" class="form-control @error('horas') inválido @enderror" id="horas" name="horas"
                    placeholder="Horas..." value="{{ old('horas', $certificado->horas) }}"
                    required>
                @error('horas')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="data" class="form-label"><strong>Data Certificado:*</strong></label>
                <input type="date" id="data" name="data" class="form-control @error('data') inválida @enderror"
                    value="{{ old('data', $certificado->data) }}" required>
                @error('data')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-between">
                <a href="{{ route('certificados.index') }}" class="btn btn-outline-danger">Cancelar</a>
                  <button type="submit" class="btn btn-outline-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Atualizar
                  </button>
            </div>
        </form>
    </div>
</div>
@endsection