@extends('layouts.admin')

@section('title', 'Mensagens de Contato')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Mensagens de Contato</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Mensagens</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12" style="max-width: 1200px;">
                
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

                <!-- Busca e Filtros -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-search"></i> Buscar Mensagens
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="search" class="form-label">Buscar por:</label>
                                <input type="text" class="form-control" id="search" placeholder="Nome, email, assunto ou mensagem...">
                            </div>
                            <div class="col-md-4">
                                <label for="date-filter" class="form-label">Período:</label>
                                <select class="form-select" id="date-filter">
                                    <option value="">Todos</option>
                                    <option value="hoje">Hoje</option>
                                    <option value="semana">Esta semana</option>
                                    <option value="mes">Este mês</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                                    <i class="fas fa-eraser"></i> Limpar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lista de Mensagens -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-envelope"></i> Lista de Mensagens
                            @if($mensagens->total() > 0)
                                <span class="badge bg-light text-dark ms-2">{{ $mensagens->total() }} total</span>
                            @endif
                        </h5>
                    </div>
                    <div class="card-body">
                    @if($mensagens->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover" id="mensagens-table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Assunto</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Data</th>
                                        <th width="120">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mensagens as $mensagem)
                                        <tr>
                                            <td>
                                                <strong>{{ $mensagem->subject ?? $mensagem->assunto ?? 'Sem assunto' }}</strong>
                                            </td>
                                            <td>
                                                <i class="fas fa-user me-1 text-muted"></i>{{ $mensagem->name ?? $mensagem->nome ?? 'N/A' }}
                                            </td>
                                            <td>
                                                <i class="fas fa-at me-1 text-muted"></i>{{ $mensagem->email ?? 'N/A' }}
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>{{ $mensagem->created_at->format('d/m/Y H:i') }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('mensagens.show', $mensagem->id) }}" 
                                                       class="btn btn-sm btn-primary" 
                                                       data-bs-toggle="tooltip" 
                                                       title="Visualizar mensagem">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('mensagens.destroy', $mensagem->id) }}" 
                                                          style="display: inline;"
                                                          onsubmit="return confirm('Tem certeza que deseja excluir esta mensagem?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-danger" 
                                                                data-bs-toggle="tooltip" 
                                                                title="Excluir mensagem">
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
                        @if($mensagens->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $mensagens->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-4x text-muted mb-4"></i>
                            <h4 class="text-muted">Nenhuma mensagem cadastrada</h4>
                            <p class="text-muted">Ainda não há mensagens de contato no sistema.</p>
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
        filterMessages();
    });

    // Filtros
    $('#date-filter').on('change', function() {
        filterMessages();
    });
});

function filterMessages() {
    var searchTerm = $('#search').val().toLowerCase();
    var dateFilter = $('#date-filter').val();

    $('#mensagens-table tbody tr').each(function() {
        var row = $(this);
        var show = true;

        // Filtro de busca
        if (searchTerm) {
            var text = row.text().toLowerCase();
            if (text.indexOf(searchTerm) === -1) {
                show = false;
            }
        }

        // Mostrar/ocultar linha
        row.toggle(show);
    });
}

function clearFilters() {
    $('#search').val('');
    $('#date-filter').val('');
    filterMessages();
}
</script>
@endpush
