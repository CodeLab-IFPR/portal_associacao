@extends('layouts.admin')

@section('title', 'Gestão de ATAs')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Gestão de ATAs</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active">ATAs</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container pt-2 d-flex justify-content-center">
    <div class="card-body p-4 p-lg-5 rounded-4 shadow-sm bg-white" style="max-width: 1200px; width: 100%;">
        <header class="text-center mb-4">
            <h2 class="fs-4 fw-medium mb-3">{{ __('Gestão de ATAs') }}</h2>
            <p class="text-muted">{{ __("Visualize e gerencie as Atas de Reuniões e Assembleias do sistema.") }}</p>
        </header>

        <!-- Seção de Ações -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-cogs"></i> Ações e Busca</h5>
            </div>
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control" 
                                   id="searchAtas" 
                                   placeholder="Buscar por título, descrição ou data..."
                                   value="">
                            <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        @if(auth()->user()->hasRole('Admin'))
                        <a href="{{ route('admin.atas.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nova ATA
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertas -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Lista de ATAs -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-file-pdf"></i> Lista de ATAs</h5>
            </div>
            <div class="card-body">
                @if($atas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover" id="atasTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Título</th>
                                    <th>Descrição</th>
                                    <th>Arquivo</th>
                                    <th>Data de Criação</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($atas as $ata)
                                <tr>
                                    <td><span class="badge bg-secondary">#{{ $ata->id }}</span></td>
                                    <td><strong>{{ $ata->titulo }}</strong></td>
                                    <td class="text-muted">{{ \Illuminate\Support\Str::limit($ata->descricao, 50) }}</td>
                                    <td>
                                        @if($ata->arquivo)
                                            <span class="badge bg-success">
                                                <i class="fas fa-file-pdf me-1"></i>PDF Disponível
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>Sem arquivo
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i>{{ $ata->data_criada }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.atas.show', $ata) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               title="Visualizar"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if($ata->arquivo)
                                            <a href="{{ route('admin.atas.download', $ata) }}" 
                                               class="btn btn-sm btn-outline-success" 
                                               title="Download PDF"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            @endif

                                            @if(auth()->user()->hasRole('Admin'))
                                            <a href="{{ route('admin.atas.edit', $ata) }}" 
                                               class="btn btn-sm btn-outline-warning" 
                                               title="Editar"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Excluir"
                                                    data-bs-toggle="tooltip"
                                                    onclick="confirmDelete('{{ $ata->id }}', '{{ $ata->titulo }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $atas->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-pdf fa-4x text-muted mb-4"></i>
                        <h5 class="text-muted mb-3">Nenhuma ATA cadastrada</h5>
                        <p class="text-muted mb-4">Ainda não há ATAs registradas no sistema.</p>
                        @if(auth()->user()->hasRole('Admin'))
                        <a href="{{ route('admin.atas.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Cadastrar Primeira ATA
                        </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Forms ocultos para delete -->
        @if(auth()->user()->hasRole('Admin'))
        @foreach($atas as $ata)
        <form id="delete-form-{{ $ata->id }}" 
              action="{{ route('admin.atas.destroy', $ata) }}" 
              method="POST" 
              style="display: none;">
            @csrf
            @method('DELETE')
        </form>
        @endforeach
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Busca personalizada
    const searchInput = document.getElementById('searchAtas');
    const clearButton = document.getElementById('clearSearch');
    const table = document.getElementById('atasTable');
    
    if (searchInput && table) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            
            Array.from(rows).forEach(row => {
                const title = row.cells[1].textContent.toLowerCase();
                const description = row.cells[2].textContent.toLowerCase();
                const date = row.cells[4].textContent.toLowerCase();
                
                if (title.includes(searchTerm) || description.includes(searchTerm) || date.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        clearButton.addEventListener('click', function() {
            searchInput.value = '';
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            Array.from(rows).forEach(row => {
                row.style.display = '';
            });
        });
    }
    
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

function confirmDelete(ataId, ataTitle) {
    if (confirm(`Tem certeza que deseja excluir a ATA "${ataTitle}"?\n\nEsta ação não poderá ser desfeita.`)) {
        document.getElementById('delete-form-' + ataId).submit();
    }
}
</script>
@endsection
