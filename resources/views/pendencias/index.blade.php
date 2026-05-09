@extends('layouts.admin')

@section('title')
Pendências
@endsection

@section('content')
@php
    $mesesAbrev = [1=>'Jan',2=>'Fev',3=>'Mar',4=>'Abr',5=>'Mai',6=>'Jun',7=>'Jul',8=>'Ago',9=>'Set',10=>'Out',11=>'Nov',12=>'Dez'];
    $monthOptions = collect($availableMonths ?? [])->map(function ($ym) use ($mesesAbrev) {
        [$year, $month] = explode('-', $ym);
        return [
            'value' => $ym,
            'label' => $mesesAbrev[(int)$month] . '/' . substr($year, -2),
        ];
    });
@endphp
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Pendências</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pendências</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">


    <div class="container-fluid mx-auto" style="max-width: 720px;">

        @if($upcomingCount > 0)
            <div class="flex justify-center align-items-center text-center mb-2" role="alert">
                <i class="bi bi-exclamation-circle me-2 fs-5"></i>
                <span>
                    Você possui <strong>{{ $upcomingCount }}</strong>
                    {{ $upcomingCount === 1 ? 'mensalidade vencendo' : 'mensalidades vencendo' }}
                    em breve
                </span>
            </div>
        @endif

        <form id="filterForm" method="GET" action="{{ route('pendencias.index') }}" class="mb-3">
            <div class="row g-2 align-items-center">
                <div class="col-sm-4">
                    <select name="status" class="form-select">
                        <option value="">Status</option>
                        <option value="pendente" @selected(request('status') === 'pendente')>Pendente</option>
                        <option value="paga" @selected(request('status') === 'paga')>Paga</option>
                        <option value="vencida" @selected(request('status') === 'vencida')>Vencida</option>
                    </select>
                </div>
                <div class="col-sm-4">
                    <select name="month" class="form-select">
                        <option value="">Mês</option>
                        @foreach($monthOptions as $opt)
                            <option value="{{ $opt['value'] }}" @selected(request('month') === $opt['value'])>{{ $opt['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-funnel me-1"></i>Filtrar
                    </button>
                    @if(request('status') || request('month') || request('search'))
                        <a href="{{ route('pendencias.index') }}" class="btn btn-outline-secondary">Limpar</a>
                    @endif
                </div>
                <div class="col-12">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Buscar por valor (R$) ou texto nas observações">
                    </div>
                </div>
            </div>
        </form>

        <div id="cards-container">
            @forelse ($installments as $installment)
                @include('pendencias._card', ['installment' => $installment])
            @empty
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5 text-muted">
                        @if(request('status') || request('month') || request('search'))
                            Nenhuma mensalidade encontrada para os filtros selecionados.
                        @else
                            Você não possui mensalidades.
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        <div id="scroll-sentinel" class="text-center py-3" style="min-height: 40px;">
            <div id="loading-indicator" class="spinner-border text-secondary" role="status" style="display: none;">
                <span class="visually-hidden">Carregando...</span>
            </div>
        </div>

    </div>
</div>

{{-- ======================================================
     Modal: Visualizar Boleto (PDF)
     ====================================================== --}}
<div class="modal fade" id="previewBoletoModal" tabindex="-1" aria-labelledby="previewBoletoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="height: 90vh;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="previewBoletoModalLabel">
                    <i class="bi bi-eye me-2"></i>Boleto — Parcela <span id="preview-installment-number"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="preview-boleto-iframe"
                        src=""
                        style="width: 100%; height: 100%; border: 0;"
                        title="Pré-visualização do boleto"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Visualizar boleto — event delegation (cards dinâmicos)
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-preview-boleto');
            if (!btn) return;

            const url    = btn.dataset.url;
            const number = btn.dataset.number;

            document.getElementById('preview-installment-number').textContent = '#' + number;
            document.getElementById('preview-boleto-iframe').src = url;

            new bootstrap.Modal(document.getElementById('previewBoletoModal')).show();
        });

        document.getElementById('previewBoletoModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('preview-boleto-iframe').src = '';
        });

        const sentinel = document.getElementById('scroll-sentinel');
        const container = document.getElementById('cards-container');
        const loader = document.getElementById('loading-indicator');

        let nextPage = 2;
        let isLoading = false;
        let hasMore = @json($installments->hasMorePages());

        if (!hasMore || !sentinel) return;

        const observer = new IntersectionObserver(async (entries) => {
            if (!entries[0].isIntersecting || isLoading || !hasMore) return;

            isLoading = true;
            loader.style.display = 'inline-block';

            const params = new URLSearchParams(window.location.search);
            params.set('page', nextPage);

            try {
                const res = await fetch(`{{ route('pendencias.loadMore') }}?${params.toString()}`, {
                    headers: { 'Accept': 'application/json' }
                });
                if (!res.ok) throw new Error('Falha ao carregar');
                const data = await res.json();

                container.insertAdjacentHTML('beforeend', data.html);
                hasMore = data.hasMore;
                nextPage++;

                if (!hasMore) observer.disconnect();
            } catch (err) {
                console.error(err);
                hasMore = false;
                observer.disconnect();
            } finally {
                isLoading = false;
                loader.style.display = 'none';
            }
        }, { rootMargin: '200px' });

        observer.observe(sentinel);
    });
</script>
@endsection
