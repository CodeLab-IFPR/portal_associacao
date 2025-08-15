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

<div class="app-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-pdf me-2"></i>Lista de ATAs
                    </h5>
                    @if(auth()->user()->hasRole('Admin'))
                    <a href="{{ route('admin.atas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nova ATA
                    </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($atas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
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
                                    <td>{{ $ata->id }}</td>
                                    <td>{{ $ata->titulo }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($ata->descricao, 50) }}</td>
                                    <td>
                                        @if($ata->arquivo)
                                            <span class="badge bg-success">
                                                <i class="fas fa-file-pdf me-1"></i>PDF
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>Sem arquivo
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $ata->data_criada }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.atas.show', $ata) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               title="Visualizar">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if($ata->arquivo)
                                            <a href="{{ route('admin.atas.download', $ata) }}" 
                                               class="btn btn-sm btn-outline-success" 
                                               title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            @endif

                                            @if(auth()->user()->hasRole('Admin') && auth()->user()->getRoleNames()->contains('Admin'))
                                            <a href="{{ route('admin.atas.edit', $ata) }}" 
                                               class="btn btn-sm btn-outline-warning" 
                                               title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <form action="{{ route('admin.atas.destroy', $ata) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Tem certeza que deseja excluir esta ATA?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        title="Excluir">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $atas->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-file-pdf fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Nenhuma ATA cadastrada</h5>
                        <p class="text-muted">Clique no botão "Nova ATA" para cadastrar a primeira ATA.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
