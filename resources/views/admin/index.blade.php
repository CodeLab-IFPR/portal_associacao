@extends('layouts.admin')

<!-- Titulo -->
@section('title')
Dashboard
@endsection
<!-- Titulo -->

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Home</h3>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <!-- Seção de ATAs -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-pdf me-2"></i>ATAs Recentes
                    </h5>
                    <div class="btn-group">
                        <a href="{{ route('admin.atas.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-list me-2"></i>Ver Todas
                        </a>
                        @if(auth()->user()->hasRole('Admin') && auth()->user()->getRoleNames()->contains('Admin'))
                        <a href="{{ route('admin.atas.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-2"></i>Nova ATA
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                @php
                    $atasRecentes = \App\Models\Ata::latest()->take(5)->get();
                @endphp

                @if($atasRecentes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Título</th>
                                    <th>Descrição</th>
                                    <th>Data</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($atasRecentes as $ata)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-file-pdf text-danger me-2"></i>
                                            <strong>{{ $ata->titulo }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ \Illuminate\Support\Str::limit($ata->descricao, 60) }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $ata->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.atas.show', $ata) }}" 
                                               class="btn btn-outline-info" 
                                               title="Visualizar">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if($ata->arquivo)
                                            <a href="{{ route('admin.atas.download', $ata) }}" 
                                               class="btn btn-outline-success" 
                                               title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            @endif

                                            @if(auth()->user()->hasRole('Admin') && auth()->user()->getRoleNames()->contains('Admin'))
                                            <a href="{{ route('admin.atas.edit', $ata) }}" 
                                               class="btn btn-outline-warning" 
                                               title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-file-pdf fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">Nenhuma ATA cadastrada</h6>
                        <p class="text-muted mb-3">As ATAs aparecerão aqui quando forem cadastradas.</p>
                        @if(auth()->user()->hasRole('Admin') && auth()->user()->getRoleNames()->contains('Admin'))
                        <a href="{{ route('admin.atas.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Cadastrar Primeira ATA
                        </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Estatísticas Rápidas -->
        <div class="row mt-4">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ \App\Models\Ata::count() }}</h3>
                        <p>ATAs Cadastradas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <a href="{{ route('admin.atas.index') }}" class="small-box-footer">
                        Ver todas <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ \App\Models\User::count() }}</h3>
                        <p>Membros Cadastrados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    @if(auth()->user()->hasRole('Admin') && auth()->user()->getRoleNames()->contains('Admin'))
                    <a href="{{ route('users.index') }}" class="small-box-footer">
                        Ver todos <i class="fas fa-arrow-circle-right"></i>
                    </a>
                    @else
                    <div class="small-box-footer bg-light text-muted">
                        <i class="fas fa-lock me-1"></i>Acesso Restrito
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ \App\Models\User::where('ativo', true)->count() }}</h3>
                        <p>Membros Ativos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    @if(auth()->user()->hasRole('Admin') && auth()->user()->getRoleNames()->contains('Admin'))
                    <a href="{{ route('users.index') }}" class="small-box-footer">
                        Ver detalhes <i class="fas fa-arrow-circle-right"></i>
                    </a>
                    @else
                    <div class="small-box-footer bg-light text-muted">
                        <i class="fas fa-lock me-1"></i>Acesso Restrito
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ \App\Models\Ata::whereMonth('created_at', now()->month)->count() }}</h3>
                        <p>ATAs Este Mês</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <a href="{{ route('admin.atas.index') }}" class="small-box-footer">
                        Ver detalhes <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection