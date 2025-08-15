@extends('layouts.admin')

@section('title', 'Visualizar ATA - ' . $ata->titulo)

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Visualizar ATA</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.atas.index') }}">ATAs</a></li>
                    <li class="breadcrumb-item active">Visualizar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-pdf me-2"></i>{{ $ata->titulo }}
                    </h5>
                    <div class="btn-group">
                        @if($ata->arquivo)
                        <a href="{{ route('admin.atas.download', $ata) }}" 
                           class="btn btn-success">
                            <i class="fas fa-download me-2"></i>Download PDF
                        </a>
                        @endif
                        
                        @if(auth()->user()->hasRole('Admin') && auth()->user()->getRoleNames()->contains('Admin'))
                        <a href="{{ route('admin.atas.edit', $ata) }}" 
                           class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Editar
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h6 class="text-primary fw-bold mb-3">
                            <i class="fas fa-info-circle me-2"></i>Informações da ATA
                        </h6>
                        
                        <div class="mb-4">
                            <strong class="text-muted">Título:</strong>
                            <p class="mb-0 mt-1">{{ $ata->titulo }}</p>
                        </div>

                        <div class="mb-4">
                            <strong class="text-muted">Descrição:</strong>
                            <p class="mb-0 mt-1">{{ $ata->descricao }}</p>
                        </div>

                        <div class="mb-4">
                            <strong class="text-muted">Data de Criação:</strong>
                            <p class="mb-0 mt-1">{{ $ata->data_criada }}</p>
                        </div>

                        @if($ata->arquivo)
                        <div class="mb-4">
                            <strong class="text-muted">Arquivo:</strong>
                            <div class="mt-2">
                                <div class="d-flex align-items-center p-3 bg-light border rounded">
                                    <i class="fas fa-file-pdf fa-2x text-danger me-3"></i>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $ata->arquivo_original }}</h6>
                                        <small class="text-muted">Arquivo PDF</small>
                                    </div>
                                    <a href="{{ route('admin.atas.download', $ata) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-download me-1"></i>Download
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-chart-bar me-2"></i>Estatísticas
                                </h6>
                                
                                <div class="mb-3">
                                    <small class="text-muted">Status do Arquivo:</small><br>
                                    @if($ata->arquivo)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Arquivo Disponível
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times me-1"></i>Sem Arquivo
                                        </span>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <small class="text-muted">Criado em:</small><br>
                                    <strong>{{ $ata->created_at->format('d/m/Y') }}</strong>
                                </div>

                                @if($ata->updated_at != $ata->created_at)
                                <div class="mb-3">
                                    <small class="text-muted">Última atualização:</small><br>
                                    <strong>{{ $ata->updated_at->format('d/m/Y H:i') }}</strong>
                                </div>
                                @endif
                            </div>
                        </div>

                        @if(auth()->user()->hasRole('Admin') && auth()->user()->getRoleNames()->contains('Admin'))
                        <div class="card bg-light mt-3">
                            <div class="card-body">
                                <h6 class="card-title text-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Zona de Perigo
                                </h6>
                                
                                <form action="{{ route('admin.atas.destroy', $ata) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Tem certeza que deseja excluir esta ATA? Esta ação não pode ser desfeita!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm w-100">
                                        <i class="fas fa-trash me-2"></i>Excluir ATA
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
