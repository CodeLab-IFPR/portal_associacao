@extends('layouts.admin')

@section('title', 'Editar Frases da Página Inicial')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Editar Frases</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active">Editar Frases</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container pt-2 d-flex justify-content-center">
    <div class="card-body p-4 p-lg-5 rounded-4 shadow-sm bg-white" style="max-width: 1000px; width: 100%;">
        <header class="text-center mb-4">
            <h2 class="fs-4 fw-medium mb-3">{{ __('Editar Conteúdo da Página Inicial') }}</h2>
            <p class="text-muted">{{ __("Configure as informações principais que serão exibidas na página inicial do site.") }}</p>
        </header>

        <form action="{{ route('admin.frase_inicio.atualizar') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Informações Principais -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-home"></i> Informações Principais</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="titulo" class="form-label"><strong>Título Principal:</strong></label>
                                <input type="text" 
                                       name="titulo" 
                                       id="titulo" 
                                       class="form-control @error('titulo') is-invalid @enderror" 
                                       value="{{ old('titulo', $fraseInicio->titulo ?? 'AMAER') }}" 
                                       placeholder="Nome da associação..."
                                       required>
                                @error('titulo')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="localizacao" class="form-label"><strong>Localização:</strong></label>
                                <input type="text" 
                                       name="localizacao" 
                                       id="localizacao" 
                                       class="form-control @error('localizacao') is-invalid @enderror" 
                                       value="{{ old('localizacao', $fraseInicio->localizacao ?? 'Maringá - PR') }}" 
                                       placeholder="Cidade - Estado"
                                       required>
                                @error('localizacao')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="subtitulo" class="form-label"><strong>Subtítulo:</strong></label>
                                <input type="text" 
                                       name="subtitulo" 
                                       id="subtitulo" 
                                       class="form-control @error('subtitulo') is-invalid @enderror" 
                                       value="{{ old('subtitulo', $fraseInicio->subtitulo ?? 'Associação de Aeromodelismo e Automodelismo') }}" 
                                       placeholder="Descrição resumida da associação..."
                                       required>
                                @error('subtitulo')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conteúdo Textual -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Conteúdo Textual</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="frase_inicio" class="form-label"><strong>Frase de Boas-vindas:</strong></label>
                                <textarea name="frase_inicio" 
                                          id="frase_inicio" 
                                          class="form-control @error('frase_inicio') is-invalid @enderror" 
                                          rows="3" 
                                          placeholder="Digite uma frase de boas-vindas aos visitantes..."
                                          required>{{ old('frase_inicio', $fraseInicio->frase ?? '') }}</textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Esta frase aparecerá como destaque na página inicial.
                                </div>
                                @error('frase_inicio')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="descricao" class="form-label"><strong>Descrição da Associação:</strong></label>
                                <textarea name="descricao" 
                                          id="descricao" 
                                          class="form-control @error('descricao') is-invalid @enderror" 
                                          rows="4" 
                                          placeholder="Descreva os objetivos, atividades e características da associação..."
                                          required>{{ old('descricao', $fraseInicio->descricao ?? 'Uma associação dedicada aos amantes do aeromodelismo e automodelismo') }}</textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Descrição detalhada que aparecerá na seção "Sobre" da página inicial.
                                </div>
                                @error('descricao')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações de Associação -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-user-plus"></i> Informações de Associação</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="como_associar_se" class="form-label"><strong>Como Associar-se:</strong></label>
                                <textarea name="como_associar_se" 
                                          id="como_associar_se" 
                                          class="form-control @error('como_associar_se') is-invalid @enderror" 
                                          rows="4" 
                                          placeholder="Descreva o processo para se tornar um membro da associação...">{{ old('como_associar_se', $fraseInicio->como_associar_se ?? '') }}</textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Instruções sobre como interessados podem se associar e participar das atividades.
                                </div>
                                @error('como_associar_se')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <a href="{{ route('admin') }}" class="btn btn-secondary me-md-2">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>
@endsection