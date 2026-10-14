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
    $selectedStartDate = request('start_date');
    $selectedEndDate = request('end_date');
@endphp
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .pendencias-page {
        max-width: 1180px;
        margin: 0 auto;
    }

    .summary-card {
        border: 0;
        border-radius: 1rem;
        box-shadow: 0 0.35rem 1.25rem rgba(16, 24, 40, 0.08);
        overflow: hidden;
    }

    .summary-card .card-body {
        padding: 1rem 1.1rem;
    }

    .summary-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(13, 110, 253, 0.12);
        color: #0d6efd;
        flex-shrink: 0;
    }

    .summary-value {
        font-size: 1.25rem;
        font-weight: 800;
        line-height: 1.1;
    }

    .filter-panel {
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 0.25rem 1rem rgba(16, 24, 40, 0.05);
    }

    .period-filter {
        min-height: 42px;
        cursor: pointer;
    }

    .load-more-wrap {
        display: flex;
        justify-content: center;
        margin: 1.5rem 0 0.5rem;
    }

    .load-more-btn {
        min-width: 220px;
        border-radius: 999px;
        font-weight: 700;
    }

    .toast-container-custom {
        position: fixed;
        top: 1rem;
        right: 1rem;
        z-index: 1080;
    }

    .receipt-box {
        border: 1px dashed rgba(0, 0, 0, 0.12);
        border-radius: 1rem;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    }

    @media (max-width: 767.98px) {
        .summary-value {
            font-size: 1.05rem;
        }

        .load-more-btn {
            width: 100%;
            min-width: auto;
        }
    }
</style>

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0 d-flex align-items-center gap-2 flex-wrap">
                    Pendências
                    @if($isAdmin)
                        <span class="badge bg-dark">Admin</span>
                    @endif
                </h3>
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
    <div class="container-fluid pendencias-page px-3 px-md-4">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
            <div>
                <div class="text-muted small">Acompanhe e gerencie os pagamentos dos membros da associação.</div>
            </div>

            @if($isAdmin)
                <a href="{{ route('invoices.create') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i>Cadastrar Fatura
                </a>
            @endif
        </div>

        @if($isAdmin)
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card summary-card h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="summary-icon">
                                <i class="bi bi-cash-coin fs-4"></i>
                            </div>
                            <div>
                                <div class="text-muted small mb-1">Valor Total Pendente</div>
                                <div class="summary-value">R$ {{ number_format($summary['total_pendente'], 2, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card summary-card h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="summary-icon">
                                <i class="bi bi-receipt fs-4"></i>
                            </div>
                            <div>
                                <div class="text-muted small mb-1">Total de Pendências</div>
                                <div class="summary-value">{{ number_format($summary['total_pendencias'], 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card summary-card h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="summary-icon">
                                <i class="bi bi-exclamation-triangle fs-4"></i>
                            </div>
                            <div>
                                <div class="text-muted small mb-1">Pendências Vencidas</div>
                                <div class="summary-value">{{ number_format($summary['pendencias_vencidas'], 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="filter-panel p-3 p-md-4 mb-4">
            <form id="filterForm" method="GET" action="{{ route('pendencias.index') }}">
                <div class="row g-3 align-items-end">
                    @if($isAdmin)
                        <div class="col-lg-5">
                            <label for="search" class="form-label fw-semibold mb-1">Buscar por associado / CPF</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                                <input type="text" id="search" name="search" class="form-control"
                                       value="{{ request('search') }}"
                                       placeholder="Digite o nome ou CPF do associado">
                            </div>
                        </div>
                    @endif
                    <div class="col-lg-2 col-md-4">
                        <label for="status" class="form-label fw-semibold mb-1">Status</label>
                        <select id="status" name="status" class="form-select">
                            <option value="">Todos</option>
                            <option value="pendente" @selected(request('status') === 'pendente')>Pendente</option>
                            <option value="paga" @selected(request('status') === 'paga')>Paga</option>
                            <option value="vencida" @selected(request('status') === 'vencida')>Vencida</option>
                        </select>
                    </div>
                    <div class="col-lg-5">
                        <label for="periodRange" class="form-label fw-semibold mb-1">Período</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-calendar3"></i></span>
                            <input type="text"
                                   id="periodRange"
                                   class="form-control period-filter"
                                   placeholder="Selecione o período no calendário"
                                   value="{{ $selectedStartDate ? \Carbon\Carbon::parse($selectedStartDate)->format('d/m/Y') . ($selectedEndDate ? ' até ' . \Carbon\Carbon::parse($selectedEndDate)->format('d/m/Y') : '') : '' }}"
                                   readonly>
                            <input type="hidden" name="start_date" id="startDate" value="{{ $selectedStartDate }}">
                            <input type="hidden" name="end_date" id="endDate" value="{{ $selectedEndDate }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4">
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="bi bi-funnel me-1"></i>Filtrar
                            </button>
                            @if(request('status') || request('start_date') || request('end_date') || request('search'))
                                <a href="{{ route('pendencias.index') }}" class="btn btn-outline-secondary">Limpar</a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div id="cards-container">
            @forelse ($installments as $installment)
                @include('pendencias._card', ['installment' => $installment, 'isAdmin' => $isAdmin])
            @empty
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body text-center py-5 text-muted">
                        @if(request('status') || request('month') || request('search'))
                            Nenhuma pendência encontrada para os filtros selecionados.
                        @else
                            Nenhuma pendência encontrada.
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        @if($installments->hasMorePages())
            <div class="load-more-wrap">
                <button type="button" id="load-more-btn" class="btn btn-outline-primary load-more-btn">
                    <span class="btn-label"><i class="bi bi-chevron-down me-1"></i>Mostrar mais</span>
                    <span class="spinner-border spinner-border-sm d-none ms-2" id="load-more-spinner" role="status" aria-hidden="true"></span>
                </button>
            </div>
        @endif

        <div class="toast-container-custom" id="toast-container"></div>
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

<div class="modal fade" id="proofModal" tabindex="-1" aria-labelledby="proofModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="proofModalLabel">
                    <i class="bi bi-receipt-cutoff me-2"></i>Ver comprovante
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="receipt-box p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="text-muted small">Associado</div>
                            <div class="fw-bold" id="proof-user-name"></div>
                            <div class="text-muted small" id="proof-user-cpf"></div>
                        </div>
                        <span class="badge bg-success" id="proof-status">PAGO</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-muted small">Parcela</div>
                            <div class="fw-semibold" id="proof-installment"></div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Valor</div>
                            <div class="fw-semibold" id="proof-amount"></div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Vencimento</div>
                            <div class="fw-semibold" id="proof-due-date"></div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Baixado em</div>
                            <div class="fw-semibold" id="proof-payment-date"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="settleConfirmModal" tabindex="-1" aria-labelledby="settleConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-warning-subtle">
                <h5 class="modal-title" id="settleConfirmModalLabel">
                    <i class="bi bi-exclamation-triangle me-2 text-warning"></i>Confirmar baixa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-2">Registrar baixa desta pendência?</p>
                <div class="receipt-box p-3">
                    <div class="small text-muted">Associado</div>
                    <div class="fw-bold" id="settle-user-name"></div>
                    <div class="row g-3 mt-1">
                        <div class="col-6">
                            <div class="small text-muted">Parcela</div>
                            <div class="fw-semibold" id="settle-installment-number"></div>
                        </div>
                        <div class="col-6">
                            <div class="small text-muted">Valor</div>
                            <div class="fw-semibold" id="settle-amount"></div>
                        </div>
                    </div>
                </div>
                <p class="text-muted small mb-0 mt-3">Após confirmar, o status será atualizado para <strong>PAGO</strong>.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="settle-confirm-btn" class="btn btn-success">
                    <i class="bi bi-check2-circle me-1"></i>Confirmar baixa
                </button>
            </div>
        </div>
    </div>
</div>

<div id="pendencias-config"
     data-next-page="{{ $installments->currentPage() + 1 }}"
     data-has-more="{{ $installments->hasMorePages() ? 1 : 0 }}"
     data-load-more-url="{{ route('pendencias.loadMore') }}"
     data-success="{{ e(session('success')) }}"
     data-error="{{ e(session('error')) }}"
     hidden></div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const config = document.getElementById('pendencias-config');
        const toastContainer = document.getElementById('toast-container');
        const settleConfirmModalEl = document.getElementById('settleConfirmModal');
        const settleConfirmModal = settleConfirmModalEl ? new bootstrap.Modal(settleConfirmModalEl) : null;
        const settleConfirmBtn = document.getElementById('settle-confirm-btn');
        let pendingSettleForm = null;

        function showToast(message, type = 'primary') {
            if (!toastContainer) return;

            const toastEl = document.createElement('div');
            toastEl.className = 'toast align-items-center text-bg-' + type + ' border-0 mb-2';
            toastEl.setAttribute('role', 'alert');
            toastEl.setAttribute('aria-live', 'assertive');
            toastEl.setAttribute('aria-atomic', 'true');
            toastEl.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;

            toastContainer.appendChild(toastEl);
            const toast = new bootstrap.Toast(toastEl, { delay: 3500 });
            toast.show();

            toastEl.addEventListener('hidden.bs.toast', function () {
                toastEl.remove();
            });
        }

        const initialSuccess = config?.dataset.success || '';
        const initialError = config?.dataset.error || '';

        if (initialSuccess) {
            showToast(initialSuccess, 'success');
        }

        if (initialError) {
            showToast(initialError, 'danger');
        }

        const periodRange = document.getElementById('periodRange');
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');

        // Formata um objeto Date (no horário LOCAL) para "YYYY-MM-DD",
        // sem passar por toISOString() (que converte para UTC e pode
        // "voltar" um dia dependendo do fuso horário do navegador).
        const formatIsoLocal = (date) => {
            const d = String(date.getDate()).padStart(2, '0');
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const y = date.getFullYear();
            return `${y}-${m}-${d}`;
        };

        // Converte uma string "YYYY-MM-DD" (vinda do PHP/request) em um
        // objeto Date à meia-noite no horário LOCAL. Isso evita o bug de
        // passar a string ISO direto pro Flatpickr: como o Flatpickr está
        // configurado com dateFormat 'd/m/Y', ele cairia no parser nativo
        // do JS, que interpreta "YYYY-MM-DD" como UTC e, ao converter para
        // o fuso local (ex: Brasil, UTC-3), "voltava" para o dia anterior —
        // fazendo o filtro exibir/selecionar uma data diferente da enviada.
        const parseIsoAsLocalDate = (iso) => {
            if (!iso) return null;
            const parts = iso.split('-').map(Number);
            const [y, m, d] = parts;
            if (!y || !m || !d) return null;
            return new Date(y, m - 1, d);
        };

        if (periodRange && window.flatpickr) {
            const initialStart = parseIsoAsLocalDate(startDateInput?.value);
            const initialEnd = parseIsoAsLocalDate(endDateInput?.value);

            const periodPicker = flatpickr(periodRange, {
                mode: 'range',
                dateFormat: 'd/m/Y',
                allowInput: false,
                defaultDate: initialStart
                    ? (initialEnd ? [initialStart, initialEnd] : [initialStart])
                    : [],
                onChange: function (selectedDates, dateStr) {
                    if (selectedDates.length === 0) {
                        if (startDateInput) startDateInput.value = '';
                        if (endDateInput) endDateInput.value = '';
                        periodRange.value = '';
                        return;
                    }

                    if (selectedDates[0] && startDateInput) startDateInput.value = formatIsoLocal(selectedDates[0]);
                    if (selectedDates[1] && endDateInput) {
                        endDateInput.value = formatIsoLocal(selectedDates[1]);
                    } else if (startDateInput) {
                        endDateInput.value = '';
                    }
                }
            });

            const filterForm = document.getElementById('filterForm');
            if (filterForm) {
                filterForm.addEventListener('submit', function () {
                    const selectedDates = periodPicker.selectedDates || [];

                    if (!selectedDates.length) {
                        if (startDateInput) startDateInput.value = '';
                        if (endDateInput) endDateInput.value = '';
                        return;
                    }

                    if (selectedDates[0] && startDateInput) startDateInput.value = formatIsoLocal(selectedDates[0]);
                    if (selectedDates[1] && endDateInput) {
                        endDateInput.value = formatIsoLocal(selectedDates[1]);
                    } else if (startDateInput) {
                        endDateInput.value = '';
                    }
                });
            }
        }

        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-preview-boleto');
            if (!btn) return;

            const url = btn.dataset.url;
            const number = btn.dataset.number;

            document.getElementById('preview-installment-number').textContent = '#' + number;
            document.getElementById('preview-boleto-iframe').src = url;

            new bootstrap.Modal(document.getElementById('previewBoletoModal')).show();
        });

        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-view-proof');
            if (!btn) return;

            document.getElementById('proof-user-name').textContent = btn.dataset.userName || '-';
            document.getElementById('proof-user-cpf').textContent = btn.dataset.userCpf || '';
            document.getElementById('proof-installment').textContent = '#' + (btn.dataset.number || '-');
            document.getElementById('proof-amount').textContent = btn.dataset.amount || '-';
            document.getElementById('proof-due-date').textContent = btn.dataset.dueDate || '-';
            document.getElementById('proof-payment-date').textContent = btn.dataset.paymentDate || '-';

            new bootstrap.Modal(document.getElementById('proofModal')).show();
        });

        document.addEventListener('submit', function (e) {
            const form = e.target.closest('.js-settle-form');
            if (!form) return;

            e.preventDefault();
            pendingSettleForm = form;

            document.getElementById('settle-user-name').textContent = form.dataset.userName || '-';
            document.getElementById('settle-installment-number').textContent = '#' + (form.dataset.installmentNumber || '-');
            document.getElementById('settle-amount').textContent = form.dataset.amount || '-';

            if (settleConfirmModal) {
                settleConfirmModal.show();
            }
        });

        if (settleConfirmBtn) {
            settleConfirmBtn.addEventListener('click', function () {
                if (!pendingSettleForm) return;

                pendingSettleForm.submit();
            });
        }

        document.getElementById('previewBoletoModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('preview-boleto-iframe').src = '';
        });

        const loadMoreBtn = document.getElementById('load-more-btn');
        const loadMoreSpinner = document.getElementById('load-more-spinner');
        const container = document.getElementById('cards-container');

        let nextPage = parseInt(config?.dataset.nextPage || '2', 10);
        let isLoading = false;
        let hasMore = (config?.dataset.hasMore || '0') === '1';
        const loadMoreUrl = config?.dataset.loadMoreUrl || '';

        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', async function () {
                if (isLoading || !hasMore) return;

                isLoading = true;
                loadMoreBtn.disabled = true;
                loadMoreSpinner.classList.remove('d-none');

                const params = new URLSearchParams(window.location.search);
                params.set('page', nextPage);

                try {
                    const res = await fetch(`${loadMoreUrl}?${params.toString()}`, {
                        headers: { 'Accept': 'application/json' }
                    });

                    if (!res.ok) throw new Error('Falha ao carregar');

                    const data = await res.json();

                    if (data.html) {
                        container.insertAdjacentHTML('beforeend', data.html);
                    }

                    hasMore = data.hasMore;
                    nextPage++;

                    if (!hasMore) {
                        loadMoreBtn.remove();
                        showToast('Todas as pendências foram exibidas.', 'secondary');
                    } else {
                        showToast('Mais pendências carregadas.', 'info');
                    }
                } catch (err) {
                    console.error(err);
                    showToast('Não foi possível carregar mais pendências.', 'danger');
                } finally {
                    isLoading = false;
                    if (loadMoreBtn) loadMoreBtn.disabled = false;
                    if (loadMoreSpinner) loadMoreSpinner.classList.add('d-none');
                }
            });
        }
    });
</script>
@endsection