@extends('layouts.admin')

@section('title')
Visualizar Documento
@endsection

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Detalhes do Documento</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('documentos.index') }}">Documentos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Visualizar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $documento->nome_original }}</h3>
                        <div class="card-tools">
                            <span class="badge bg-{{ $documento->status_color }} fs-6">
                                {{ $documento->status_label }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">ID:</th>
                                        <td>{{ $documento->id }}</td>
                                    </tr>
                                    @if(auth()->user()->hasRole('admin'))
                                        <tr>
                                            <th>Usuário:</th>
                                            <td>{{ $documento->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ $documento->user->email }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th>Nome do Arquivo:</th>
                                        <td>{{ $documento->nome_original }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tipo:</th>
                                        <td>{{ $documento->tipo_documento }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            <span class="badge bg-{{ $documento->status_color }}">
                                                {{ $documento->status_label }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Enviado em:</th>
                                        <td>{{ $documento->created_at->format('d/m/Y H:i:s') }}</td>
                                    </tr>
                                    @if($documento->aprovado_em)
                                        <tr>
                                            <th>{{ $documento->status === 'aprovado' ? 'Aprovado' : 'Rejeitado' }} em:</th>
                                            <td>{{ $documento->aprovado_em->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                        @if($documento->aprovador)
                                            <tr>
                                                <th>{{ $documento->status === 'aprovado' ? 'Aprovado' : 'Rejeitado' }} por:</th>
                                                <td>{{ $documento->aprovador->name }}</td>
                                            </tr>
                                        @endif
                                    @endif
                                </table>
                            </div>
                            <div class="col-md-6">
                                @if($documento->descricao)
                                    <h5>Descrição:</h5>
                                    <div class="border rounded p-3 mb-3">
                                        {{ $documento->descricao }}
                                    </div>
                                @endif

                                <div class="d-grid gap-2">
                                    <a href="{{ route('documentos.download', $documento) }}" 
                                       class="btn btn-success">
                                        <i class="bi bi-download"></i> Download do Arquivo
                                    </a>

                                    @if((auth()->user()->hasRole('admin') || auth()->user()->hasRole('Admin')) && $documento->status === 'pendente')
                                        <button type="button" class="btn btn-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#aprovarModal">
                                            <i class="bi bi-check"></i> Aprovar Documento
                                        </button>
                                        <button type="button" class="btn btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rejeitarModal">
                                            <i class="bi bi-x"></i> Rejeitar Documento
                                        </button>
                                    @endif

                                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('Admin'))
                                        <button type="button" class="btn btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#excluirModal">
                                            <i class="bi bi-trash"></i> Excluir Documento
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('documentos.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Voltar para Lista
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('Admin'))
    <!-- Modal Aprovar -->
    <div class="modal fade" id="aprovarModal" tabindex="-1">
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
    <div class="modal fade" id="rejeitarModal" tabindex="-1">
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
                            <label for="observacoes" class="form-label">Motivo da rejeição *</label>
                            <textarea class="form-control" id="observacoes" 
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
    <div class="modal fade" id="excluirModal" tabindex="-1">
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
@endif
@endsection
