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
                <h3 class="mb-0">Olá, {{ auth()->user()->name }}!</h3>
                <p class="mb-0">Bem-vindo ao seu painel administrativo da AMAER.</p>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        <!-- Estatísticas Rápidas -->
        @php($colCard = $isAdmin ? 'col-lg-3 col-md-6' : 'col-md-4')
        <div class="row mt-4">

            <div class="{{ $colCard }} mb-4">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">

                            <div class="bg-danger bg-opacity-10 rounded-4 p-3 me-3">
                                <i class="fas fa-dollar-sign text-danger fs-4"></i>
                            </div>

                            <div>
                                <h2 class="fw-bold text-danger mb-0">
                                    {{ $qtdVencidas }}
                                </h2>

                                <small class="text-muted">Parcelas Vencidas</small>
                            </div>

                        </div>

                        <hr>

                        <small class="text-muted d-block mb-1">Total vencido</small>

                        <h4 class="fw-bold text-danger mb-4">
                            R$ {{ number_format($totalVencida, 2, ',', '.') }}
                        </h4>

                        <a href="{{ route('pendencias.index', ['status' => 'vencida']) }}"
                        class="btn btn-outline-danger w-100 rounded-3">Ver Pendências
                            <i class="fas fa-chevron-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="{{ $colCard }} mb-4">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-warning bg-opacity-10 rounded-4 p-3 me-3">
                                <i class="fas fa-clock text-warning fs-4"></i>
                            </div>

                            <div>
                                <h2 class="fw-bold text-warning mb-0">{{ $qtdPendentes }}</h2>
                                <small class="text-muted">Parcelas Pendentes</small>
                            </div>
                        </div>
                        <hr>

                        <small class="text-muted d-block mb-1">Total pendente</small>

                        <h4 class="fw-bold text-warning mb-4">
                            R$ {{ number_format($totalPendente, 2, ',', '.') }}
                        </h4>

                        <a href="{{ route('pendencias.index', ['status' => 'pendente']) }}"
                        class="btn btn-outline-warning w-100 rounded-3">Ver Pendências
                            <i class="fas fa-chevron-right ms-2"></i>
                        </a>

                    </div>
                </div>
            </div>

            @if($isAdmin)
            <div class="{{ $colCard }} mb-4">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-success bg-opacity-10 rounded-4 p-3 me-3">
                                <i class="fas fa-users text-success fs-4"></i>
                            </div>
                            <div>
                                <h2 class="fw-bold text-success mb-0">
                                    {{ $membrosAtivos }}
                                </h2>

                                <small class="text-muted">Membros Ativos</small>
                            </div>

                        </div>

                        <hr>

                        <small class="text-muted d-block mb-1">Novos este mês</small>

                        <h4 class="fw-bold text-success mb-4">
                            {{ $membrosNovosMes }}
                        </h4>

                        <a href="{{ route('users.index') }}"
                        class="btn btn-outline-success w-100 rounded-3">
                            Ver Membros
                            <i class="fas fa-chevron-right ms-2"></i>
                        </a>

                    </div>
                </div>
            </div>
            @endif

            <!-- ATAs -->
            <div class="{{ $colCard }} mb-4">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary bg-opacity-10 rounded-4 p-3 me-3">
                                <i class="fas fa-file-alt text-primary fs-4"></i>
                            </div>
                            <div>
                                <h2 class="fw-bold text-primary mb-0">{{ $totalAtas }}</h2>

                                <small class="text-muted">ATAs Registradas</small>
                            </div>
                        </div>

                        <hr>

                        <small class="text-muted d-block mb-1">Última adicionada</small>

                        <h5 class="fw-bold text-primary mb-4">
                            {{ $ultimaAta?->created_at?->format('d/m/Y') ?? '--' }}

                        </h5>

                        <a href="{{ route('admin.atas.index') }}"
                        class="btn btn-outline-primary w-100 rounded-3">Ver todas
                            <i class="fas fa-chevron-right ms-2"></i>
                        </a>

                    </div>
                </div>
            </div>


            <!-- Resumo Financeiro -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-header bg-white border-bottom-0 pt-3">
                        <h5 class="mb-0">
                            <i class="fas fa-dollar-sign text-primary me-2"></i>
                            Resumo Financeiro
                        </h5>
                    </div>

                    <!-- Resumo Financeiro -->
                    <div class="card-body">

                        <canvas id="graficoResumo"
                            data-vencida="{{ $totalVencida }}"
                            data-pendente="{{ $totalPendente }}"
                            data-paga="{{ $totalPagaMes }}">
                        </canvas>
                        <div class="d-flex justify-content-between mb-2 mt-3">
                            <span><i class="fas fa-circle text-danger me-2"></i>Vencidas</span>
                            <strong>R$ {{ number_format($totalVencida, 2, ',', '.') }}</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-circle text-warning me-2"></i>Pendentes</span>
                            <strong>R$ {{ number_format($totalPendente, 2, ',', '.') }}</strong>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-circle text-success me-2"></i>Pagas (este mês)</span>
                            <strong>R$ {{ number_format($totalPagaMes, 2, ',', '.') }}</strong>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Próximos Vencimentos -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm border-0 rounded-4 h-auto">

                    <div class="card-header bg-white border-bottom-0 pt-3">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                            Próximos Vencimentos
                        </h5>
                    </div>

                    <div class="card-body p-0">

                        @forelse($proximosVencimentos as $parcela)
                            <div class="d-flex align-items-center justify-content-between px-3 py-3 border-bottom">
                                <div class="text-center me-1 align-self-end" style="min-width: 45px;">
                                    <div class="fw-bold fs-5 lh-1">{{ $parcela->due_date->format('d') }}</div>

                                    <small class="text-uppercase text-muted fw-semibold">
                                        {{ $months[$parcela->due_date->format('M')] }}
                                    </small>
                                </div>

                                <div class="flex-grow-1 overflow-hidden" style="min-width: 0; max-width: 50%;">
                                    <div class="fs-6 fw-semibold text-truncate">{{ optional($parcela->invoice->user)->name }}</div>

                                    <small class="text-muted">
                                        Parcela {{ $parcela->installment_number }}/{{ $parcela->invoice->installments_count }}
                                        · {{ ucfirst($parcela->invoice->periodicity) }}
                                    </small>
                                </div>

                                <div class="text-end ms-4">
                                    <div class="fw-medium">
                                        R$ {{ number_format($parcela->amount, 2, ',', '.') }}
                                    </div>

                                    <span class="badge rounded-pill text-bg-warning fw-normal">A vencer</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted px-3 py-4">Nenhum vencimento próximo.</div>
                        @endforelse

                        <div class="text-center py-3 fs-6">

                            <a href="{{ route('pendencias.index', ['status' => 'pendente']) }}"
                            class="text-decoration-none small fw-semibold">

                                Ver todas as pendências
                                <i class="fas fa-chevron-right ms-1"></i>
                            </a>

                        </div>


                    </div>
                </div>
            </div>

            <!-- Atalhos -->
            <!-- Atalhos -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm border-0 rounded-4 h-100">

                    <div class="card-header bg-white border-bottom-0 pt-3">
                        <h5 class="mb-0">
                            <i class="fas fa-bolt text-primary me-2"></i>
                            Atalhos Rápidos
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="list-group list-group-flush">

                            @if($isAdmin)
                                <a href="{{ route('users.create') }}"
                                class="list-group-item list-group-item-action rounded mb-2 border">
                                    <i class="fas fa-users text-success me-2"></i>
                                    Cadastrar Membro
                                </a>

                                <a href="{{ route('admin.atas.create') }}"
                                class="list-group-item list-group-item-action rounded mb-2 border">
                                    <i class="fas fa-file-signature text-primary me-2"></i>
                                    Adicionar ATA
                                </a>

                                <a href="{{ route('noticias.create') }}"
                                class="list-group-item list-group-item-action rounded mb-2 border">
                                    <i class="fas fa-newspaper text-info me-2"></i>
                                    Adicionar Notícias
                                </a>
                            @endif

                            <a href="{{ route('documentos.index') }}"
                            class="list-group-item list-group-item-action rounded mb-2 border">
                                <i class="fas fa-folder-open text-warning me-2"></i>
                                Ver Documentos
                            </a>

                            <a href="{{ route('invoices.index') }}"
                            class="list-group-item list-group-item-action rounded mb-2 border">
                                <i class="fas fa-file-invoice-dollar text-danger me-2"></i>
                                Ver Faturas
                            </a>

                            <a href="{{ route('pendencias.index') }}"
                            class="list-group-item list-group-item-action rounded mb-2 border">
                                <i class="fas fa-bell text-warning me-2"></i>
                                Ver Pendências
                            </a>

                            @if($isAdmin)
                                <a href="{{ route('users.index') }}"
                                class="list-group-item list-group-item-action rounded mb-2 border">
                                    <i class="fas fa-users text-success me-2"></i>
                                    Ver Membros
                                </a>
                            @endif

                            <a href="{{ route('admin.atas.index') }}"
                            class="list-group-item list-group-item-action rounded mb-2 border">
                                <i class="fas fa-file-signature text-primary me-2"></i>
                                Ver ATAs
                            </a>

                            @if($isAdmin)
                                <a href="{{ route('noticias.index') }}"
                                class="list-group-item list-group-item-action rounded mb-2 border">
                                    <i class="fas fa-newspaper text-info me-2"></i>
                                    Ver Notícias
                                </a>

                                <a href="{{ route('galeria.indexAdmin') }}"
                                class="list-group-item list-group-item-action rounded mb-2 border">
                                    <i class="fas fa-images text-secondary me-2"></i>
                                    Ver Galeria
                                </a>
                            @endif

                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="row">

            <!-- Atividades Recentes -->
            <div class="col-lg-6 mb-4">

                <div class="card shadow-sm border-0 rounded-4">

                    <div class="card-header bg-white border-bottom-0 pt-3">
                        <h5 class="mb-0">
                            <i class="fas fa-history text-primary me-2"></i>
                            Atividades Recentes
                        </h5>
                    </div>

                    <div class="card-body p-0">

                        @forelse($activities as $activity)

                            <div class="d-flex align-items-center justify-content-between px-3 py-3 border-bottom">

                                <div class="d-flex align-items-center flex-grow-1 overflow-hidden">

                                    <div class="me-3">
                                        <i class="{{ $activity->icon }} {{ $activity->color }} fa-lg"></i>
                                    </div>

                                    <div class="text-truncate">
                                        {{ $activity->descricao }}
                                    </div>

                                </div>

                                <small class="text-muted text-nowrap ms-3">
                                    {{ $activity->data?->format('d/m/Y H:i') }}
                                </small>

                            </div>

                        @empty
                            <div class="text-center text-muted px-3 py-4">Nenhuma atividade recente.</div>
                        @endforelse

                    </div>

                </div>

            </div>

            <!-- Gráfico -->
            <div class="col-lg-6 mb-4">

                <div class="card shadow-sm border-0 rounded-4">

                    <div class="card-header bg-white border-bottom-0 pt-3">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-bar text-primary me-2"></i>
                            Gráfico de Faturas
                        </h5>
                    </div>

                    <div class="card-body">
                        @if(array_sum($pagas) + array_sum($pendentes) + array_sum($vencidas) > 0)
                            <canvas id="graficoFaturas"
                                data-labels='@json($labelsMeses)'
                                data-pagas='@json($pagas)'
                                data-pendentes='@json($pendentes)'
                                data-vencidas='@json($vencidas)'
                                style="height:320px;">
                            </canvas>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-chart-bar fa-2x mb-3 opacity-25"></i>
                                <div>Nenhuma movimentação nos últimos 6 meses.</div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>

        </div>
    </div>
</div>
@endsection