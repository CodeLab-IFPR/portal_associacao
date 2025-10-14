@extends('layouts.admin')

@section('title')
@if(isset($user))
Documentos de {{ $user->name }}
@else
Documentos
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
                        Documentos
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

<div class="app-content">
    <div class="container-fluid">
        @if(isset($user))
            <!-- Card com informações do usuário -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-person-circle me-2"></i>Informações do Membro
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    @if($user->imagem)
                                        <img src="/imagens/users/{{ $user->imagem }}" alt="{{ $user->alt }}" 
                                             class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="width: 80px; height: 80px;">
                                            <i class="bi bi-person-fill text-muted" style="font-size: 2rem;"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Nome:</strong> {{ $user->name }}</p>
                                            <p><strong>CPF:</strong> {{ $user->cpf }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Email:</strong> {{ $user->email }}</p>
                                            <p><strong>Cargo:</strong> {{ $user->cargo ?? 'Não informado' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 text-end">
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
                                        <i class="bi bi-arrow-left me-1"></i>Voltar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            @if(isset($user))
                                Documentos de {{ $user->name }}
                                <span class="badge bg-info ms-2">{{ $documentos->total() }} documento(s)</span>
                            @else
                                Lista de Documentos
                            @endif
                        </h3>
                        <div class="card-tools">
                            @if(!isset($user))
                                <a href="{{ route('documentos.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus"></i> Adicionar Documento
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @if($documentos->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
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
                                                <td>{{ $documento->id }}</td>
                                                @if(!isset($user) && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('Admin')))
                                                    <td>{{ $documento->user->name }}</td>
                                                @endif
                                                <td>{{ $documento->nome_original }}</td>
                                                <td>{{ $documento->tipo_documento }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $documento->status_color }}">
                                                        {{ $documento->status_label }}
                                                    </span>
                                                </td>
                                                <td>{{ $documento->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('documentos.show', $documento) }}" 
                                                           class="btn btn-sm btn-info align-content-center" title="Visualizar">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="{{ route('documentos.download', $documento) }}" 
                                                           class="btn btn-sm btn-success align-content-center" title="Download">
                                                            <i class="bi bi-download"></i>
                                                        </a>      
                                                    @if((auth()->user()->hasRole('admin') || auth()->user()->hasRole('Admin')) && $documento->status === 'pendente')
                                                        <!-- DEBUG": Usuário é admin e documento é pendente -->
                                                        <button type="button" class="btn btn-sm btn-warning" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#aprovarModal{{ $documento->id }}"
                                                                title="Aprovar">
                                                            <i class="bi bi-check"></i> Aprovar
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#rejeitarModal{{ $documento->id }}"
                                                                title="Rejeitar">
                                                            <i class="bi bi-x"></i> Rejeitar
                                                        </button>
                                                        <a href="{{ route('documentos.edit', $documento) }}" 
                                                                class="btn btn-sm btn-warning align-content-center" 
                                                                title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif
                                                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('Admin'))
                                                        <button type="button" class="btn btn-sm btn-danger align-content-center" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#excluirModal{{ $documento->id }}"
                                                                title="Excluir">
                                                            <i class="bi bi-trash"></i>
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
                            <div class="d-flex justify-content-center">
                                {{ $documentos->links() }}
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-file-earmark-text" style="font-size: 3rem; color: #ccc;"></i>
                                <h5 class="mt-3">Nenhum documento encontrado</h5>
                                @if(isset($user))
                                    <p class="text-muted">Este membro ainda não enviou nenhum documento.</p>
                                @else
                                    <p class="text-muted">Comece adicionando um novo documento.</p>
                                    <a href="{{ route('documentos.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus"></i> Adicionar Documento
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                        <p class="text-warning"><i class="bi bi-exclamation-triangle"></i> Após aprovado, o documento não poderá mais ser excluído.</p>
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
                        <p class="text-danger"><i class="bi bi-exclamation-triangle"></i> Esta ação não pode ser desfeita.</p>
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
@endforeach
@endsection
