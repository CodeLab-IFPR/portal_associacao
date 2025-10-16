@extends('layouts.admin')

@section('title', 'Gestão de Cargos')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Gestão de Cargos</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cargos</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12" style="max-width: 1000px;">
                
                <!-- Alertas de Sucesso/Erro -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Ações -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-plus"></i> Adicionar Novo Cargo
                        </h6>
                    </div>
                    <div class="card-body py-2">
                        <a href="{{ route('cargos.create') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Criar Novo Cargo
                        </a>
                    </div>
                </div>

                <!-- Busca -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-search"></i> Buscar Cargos
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="search" class="form-label">Buscar por descrição:</label>
                                <input type="text" class="form-control" id="search" placeholder="Digite a descrição do cargo...">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-secondary w-100" onclick="clearSearch()">
                                    <i class="fas fa-eraser"></i> Limpar Busca
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lista de Cargos -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-briefcase"></i> Lista de Cargos
                            @if($cargos->total() > 0)
                                <span class="badge bg-light text-dark ms-2">{{ $cargos->total() }} total</span>
                            @endif
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($cargos->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover" id="cargos-table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th width="60">#</th>
                                            <th>Descrição</th>
                                            <th>Data de Criação</th>
                                            <th width="150">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cargos as $cargo)
                                            <tr>
                                                <td class="fw-bold text-primary">{{ $cargo->id }}</td>
                                                <td>
                                                    <i class="fas fa-briefcase me-2 text-muted"></i>
                                                    <strong>{{ $cargo->descricao }}</strong>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar-alt me-1"></i>
                                                        {{ $cargo->created_at->format('d/m/Y H:i') }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('cargos.edit', $cargo) }}" 
                                                           class="btn btn-sm btn-warning" 
                                                           data-bs-toggle="tooltip" 
                                                           title="Editar cargo">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form method="POST" action="{{ route('cargos.destroy', $cargo) }}" 
                                                              style="display: inline;"
                                                              onsubmit="return confirm('Tem certeza que deseja excluir este cargo?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="btn btn-sm btn-danger" 
                                                                    data-bs-toggle="tooltip" 
                                                                    title="Excluir cargo">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Paginação -->
                            @if($cargos->hasPages())
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $cargos->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-briefcase fa-4x text-muted mb-4"></i>
                                <h4 class="text-muted">Nenhum cargo cadastrado</h4>
                                <p class="text-muted">Ainda não há cargos no sistema.</p>
                                <a href="{{ route('cargos.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Criar Primeiro Cargo
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .table-responsive {
        border-radius: 0.375rem;
    }
    .badge {
        font-size: 0.75em;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Busca em tempo real
    $('#search').on('keyup', function() {
        filterCargos();
    });
});

function filterCargos() {
    var searchTerm = $('#search').val().toLowerCase();

    $('#cargos-table tbody tr').each(function() {
        var row = $(this);
        var descricao = row.find('td:nth-child(2)').text().toLowerCase();
        
        if (searchTerm === '' || descricao.indexOf(searchTerm) !== -1) {
            row.show();
        } else {
            row.hide();
        }
    });
}

function clearSearch() {
    $('#search').val('');
    filterCargos();
}
</script>
@endpush