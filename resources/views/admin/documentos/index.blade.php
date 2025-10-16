@extends('layouts.admin')

@section('title')
@if(isset($user))
Documentos de {{ $user->name }}
@else
Gestão de Documentos
@endif
@endsection

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">
                    @if(isset($user))
                        Documentos de {{ $user->name }}
                    @else
                        Gestão de Documentos
                    @endif
                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    @if(isset($user))
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Membros</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Documentos de {{ $user->name }}</li>
                    @else
                        <li class="breadcrumb-item active" aria-current="page">Documentos</li>
                    @endif
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container pt-2 d-flex justify-content-center">
    <div class="card-body p-4 p-lg-5 rounded-4 shadow-sm bg-white" style="max-width: 1200px; width: 100%;">
        <header class="text-center mb-4">
            <h2 class="fs-4 fw-medium mb-3">
                @if(isset($user))
                    {{ __('Documentos de ') }} {{ $user->name }}
                @else
                    {{ __('Gestão de Documentos') }}
                @endif
            </h2>
            <p class="text-muted">
                @if(isset($user))
                    {{ __("Visualize todos os documentos enviados por este membro para análise e aprovação.") }}
                @else
                    {{ __("Gerencie documentos enviados pelos membros da associação.") }}
                @endif
            </p>
        </header>

        @if(isset($user))
            <!-- Informações do Membro -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Informações do Membro</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            @if($user->imagem)
                                <img src="/imagens/users/{{ $user->imagem }}" alt="{{ $user->alt }}" 
                                     class="img-thumbnail rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 80px; height: 80px; margin: 0 auto;">
                                    <i class="fas fa-user text-muted" style="font-size: 2rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Nome:</strong> {{ $user->name }}</p>
                                    <p class="mb-2"><strong>CPF:</strong> {{ $user->cpf }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Email:</strong> {{ $user->email }}</p>
                                    <p class="mb-2"><strong>Cargo:</strong> {{ $user->cargo ?? 'Não informado' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Voltar aos Membros
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Seção de Ações -->
        @if(!isset($user))
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-cogs"></i> Ações e Busca</h5>
            </div>
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control" 
                                   id="searchDocuments" 
                                   placeholder="Buscar por usuário, documento, tipo ou status..."
                                   value="">
                            <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('documentos.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Adicionar Documento
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Lista de Documentos -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt"></i> 
                    @if(isset($user))
                        Documentos de {{ $user->name }}
                        <span class="badge bg-light text-dark ms-2">{{ $documentos->total() }} documento(s)</span>
                    @else
                        Lista de Documentos
                        <span class="badge bg-light text-dark ms-2">{{ $documentos->total() }} total</span>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                @if($documentos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover" id="documentsTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    @if(!isset($user) && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('Admin')))
                                        <th>Usuário</th>
                                    @endif
                                    <th>Documento</th>
                                    <th>Tipo</th>
                                    <th>Status</th>
                                    <th>Enviado em</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documentos as $documento)
                                    <tr>
                                        <td><span class="badge bg-secondary">#{{ $documento->id }}</span></td>
                                        @if(!isset($user) && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('Admin')))
                                            <td><strong>{{ $documento->user->name }}</strong></td>
                                        @endif
                                        <td>
                                            <i class="fas fa-file-pdf text-danger me-2"></i>
                                            {{ $documento->nome_original }}
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $documento->tipo_documento }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $documento->status_color }}">
                                                {{ $documento->status_label }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>{{ $documento->created_at->format('d/m/Y H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('documentos.show', $documento) }}" 
                                                   class="btn btn-sm btn-outline-info" 
                                                   title="Visualizar"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('documentos.download', $documento) }}" 
                                                   class="btn btn-sm btn-outline-success" 
                                                   title="Download"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-download"></i>
                                                </a>      
                                            @if((auth()->user()->hasRole('admin') || auth()->user()->hasRole('Admin')) && $documento->status === 'pendente')
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-warning" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#aprovarModal{{ $documento->id }}"
                                                        title="Aprovar">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#rejeitarModal{{ $documento->id }}"
                                                        title="Rejeitar">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <a href="{{ route('documentos.edit', $documento) }}" 
                                                   class="btn btn-sm btn-outline-warning" 
                                                   title="Editar"
                                                   data-bs-toggle="tooltip">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('Admin'))
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#excluirModal{{ $documento->id }}"
                                                        title="Excluir"
                                                        data-bs-toggle="tooltip">
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
                    
                    <!-- Paginação -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $documentos->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt fa-4x text-muted mb-4"></i>
                        <h5 class="text-muted mb-3">Nenhum documento encontrado</h5>
                        @if(isset($user))
                            <p class="text-muted mb-4">Este membro ainda não enviou nenhum documento.</p>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Voltar aos Membros
                            </a>
                        @else
                            <p class="text-muted mb-4">Comece adicionando um novo documento ao sistema.</p>
                            <a href="{{ route('documentos.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Adicionar Primeiro Documento
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Busca personalizada
    const searchInput = document.getElementById('searchDocuments');
    const clearButton = document.getElementById('clearSearch');
    const table = document.getElementById('documentsTable');
    
    if (searchInput && table) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            
            Array.from(rows).forEach(row => {
                const cells = row.getElementsByTagName('td');
                let found = false;
                
                // Busca em todas as células da linha
                Array.from(cells).forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(searchTerm)) {
                        found = true;
                    }
                });
                
                row.style.display = found ? '' : 'none';
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
</script>

<!-- Modais para aprovação, rejeição e exclusão -->
@foreach($documentos as $documento)
    @if((auth()->user()->hasRole('admin') || auth()->user()->hasRole('Admin')) && $documento->status === 'pendente')
        <!-- Modal Aprovar -->
        <div class="modal fade" id="aprovarModal{{ $documento->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Aprovar Documento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja aprovar o documento <strong>{{ $documento->nome_original }}</strong>?</p>
                        <p class="text-warning"><i class="fas fa-exclamation-triangle"></i> Após aprovado, o documento não poderá mais ser excluído.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form action="{{ route('documentos.aprovar', $documento) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success">Aprovar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Rejeitar -->
        <div class="modal fade" id="rejeitarModal{{ $documento->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('documentos.rejeitar', $documento) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header">
                            <h5 class="modal-title">Rejeitar Documento</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Rejeitar o documento <strong>{{ $documento->nome_original }}</strong>:</p>
                            <div class="mb-3">
                                <label for="observacoes{{ $documento->id }}" class="form-label">Motivo da rejeição *</label>
                                <textarea class="form-control" id="observacoes{{ $documento->id }}" 
                                          name="observacoes" rows="3" required 
                                          placeholder="Descreva o motivo da rejeição..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">Rejeitar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('Admin'))
        <!-- Modal Excluir -->
        <div class="modal fade" id="excluirModal{{ $documento->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Excluir Documento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir o documento <strong>{{ $documento->nome_original }}</strong>?</p>
                        <p class="text-danger"><i class="fas fa-exclamation-triangle"></i> Esta ação não pode ser desfeita.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form action="{{ route('documentos.destroy', $documento) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection