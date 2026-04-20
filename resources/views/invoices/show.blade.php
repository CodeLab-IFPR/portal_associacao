@extends('layouts.admin')

@section('title')
Fatura #{{ $invoice->id }}
@endsection

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Detalhes da Fatura</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">Faturas</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Fatura #{{ $invoice->id }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-9">

                {{-- Card: Dados gerais --}}
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-receipt me-2"></i>
                            Fatura #{{ $invoice->id }}
                        </h5>
                        <span class="badge
                            @if($invoice->status === 'paga') bg-success
                            @elseif($invoice->status === 'pendente') bg-warning text-dark
                            @elseif($invoice->status === 'vencida') bg-danger
                            @else bg-secondary
                            @endif fs-6">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted mb-1"><small>Associado</small></label>
                                <p class="fw-semibold mb-0">{{ $invoice->user->name }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted mb-1"><small>Periodicidade</small></label>
                                <p class="fw-semibold mb-0">{{ ucfirst($invoice->periodicity) }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-muted mb-1"><small>Valor da Parcela</small></label>
                                <p class="fw-semibold mb-0">
                                    R$ {{ number_format($invoice->installments->first()?->amount ?? 0, 2, ',', '.') }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-muted mb-1"><small>Nº de Parcelas</small></label>
                                <p class="fw-semibold mb-0">{{ $invoice->installments_count }}x</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-muted mb-1"><small>Total</small></label>
                                <p class="fw-semibold mb-0">R$ {{ number_format($invoice->total_amount, 2, ',', '.') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted mb-1"><small>Primeiro Vencimento</small></label>
                                <p class="fw-semibold mb-0">
                                    {{ \Carbon\Carbon::parse($invoice->first_due_date)->format('d/m/Y') }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted mb-1"><small>Criada em</small></label>
                                <p class="fw-semibold mb-0">
                                    {{ $invoice->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            @if($invoice->notes)
                            <div class="col-12">
                                <label class="form-label text-muted mb-1"><small>Observações</small></label>
                                <p class="mb-0">{{ $invoice->notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Card: Parcelas --}}
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-calendar2-check me-2"></i>
                            Parcelas
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Vencimento</th>
                                        <th scope="col">Valor</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Pago em</th>
                                        <th scope="col" class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($invoice->installments->sortBy('installment_number') as $installment)
                                        <tr>
                                            <td>{{ $installment->installment_number }}</td>
                                            <td>{{ \Carbon\Carbon::parse($installment->due_date)->format('d/m/Y') }}</td>
                                            <td>R$ {{ number_format($installment->amount, 2, ',', '.') }}</td>
                                            <td>
                                                <span class="badge
                                                    @if($installment->status === 'paga') bg-success
                                                    @elseif($installment->status === 'pendente') bg-warning text-dark
                                                    @elseif($installment->status === 'vencida') bg-danger
                                                    @else bg-secondary
                                                    @endif">
                                                    {{ ucfirst($installment->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $installment->payment_date
                                                    ? \Carbon\Carbon::parse($installment->payment_date)->format('d/m/Y')
                                                    : '—' }}
                                            </td>
                                            <td class="text-center">
                                                {{-- Editar parcela --}}
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-warning btn-edit-installment me-1"
                                                    title="Editar parcela"
                                                    data-id="{{ $installment->id }}"
                                                    data-number="{{ $installment->installment_number }}"
                                                    data-amount="{{ $installment->amount }}"
                                                    data-due-date="{{ \Carbon\Carbon::parse($installment->due_date)->format('Y-m-d') }}"
                                                    data-payment-date="{{ $installment->payment_date ? \Carbon\Carbon::parse($installment->payment_date)->format('Y-m-d') : '' }}"
                                                    data-status="{{ $installment->status }}"
                                                    data-installments-count="{{ $invoice->installments_count }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                {{-- Excluir parcela --}}
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger btn-delete-installment"
                                                    title="Excluir parcela"
                                                    data-id="{{ $installment->id }}"
                                                    data-number="{{ $installment->installment_number }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-3">Nenhuma parcela encontrada.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Ações da fatura --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('invoices.index') }}" class="btn btn-outline-danger">
                        <i class="bi bi-arrow-left me-1"></i>Voltar
                    </a>
                    <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-outline-warning">
                        <i class="bi bi-pencil-square me-1"></i>Editar Fatura
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ======================================================
     Modal: Editar Parcela
     ====================================================== --}}
<div class="modal fade" id="editInstallmentModal" tabindex="-1" aria-labelledby="editInstallmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editInstallmentModalLabel">
                    <i class="bi bi-pencil-square me-2"></i>Editar Parcela <span id="edit-installment-number"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editInstallmentForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">

                    <div class="row">
                        {{-- Valor --}}
                        <div class="col-md-6 mb-3">
                            <label for="edit-amount" class="form-label"><strong>Valor (R$)*</strong></label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number" id="edit-amount" name="amount"
                                    class="form-control" min="0.01" step="0.01" required>
                            </div>
                        </div>

                        {{-- Quantidade de Parcelas --}}
                        <div class="col-md-6 mb-3">
                            <label for="edit-installments-count" class="form-label"><strong>Qtd. de Parcelas*</strong></label>
                            <input type="number" id="edit-installments-count" name="installments_count"
                                class="form-control" min="1" max="60" required>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Data de Vencimento --}}
                        <div class="col-md-6 mb-3">
                            <label for="edit-due-date" class="form-label"><strong>Data de Vencimento*</strong></label>
                            <input type="date" id="edit-due-date" name="due_date"
                                class="form-control" required>
                        </div>

                        {{-- Data de Pagamento --}}
                        <div class="col-md-6 mb-3">
                            <label for="edit-payment-date" class="form-label"><strong>Data de Pagamento</strong></label>
                            <input type="date" id="edit-payment-date" name="payment_date"
                                class="form-control">
                            <div class="form-text">Preencha apenas se a parcela foi paga.</div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="mb-3">
                        <label for="edit-status" class="form-label"><strong>Status*</strong></label>
                        <select id="edit-status" name="status" class="form-select" required>
                            <option value="pendente">Pendente</option>
                            <option value="paga">Paga</option>
                            <option value="vencida">Vencida</option>
                            <option value="cancelada">Cancelada</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ======================================================
     Modal: Confirmar Exclusão de Parcela
     ====================================================== --}}
<div class="modal fade" id="deleteInstallmentModal" tabindex="-1" aria-labelledby="deleteInstallmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteInstallmentModalLabel">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir a <strong>Parcela <span id="delete-installment-number"></span></strong>?</p>
                <p class="text-danger mb-0"><i class="bi bi-exclamation-triangle me-1"></i>Esta ação não pode ser desfeita.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteInstallmentForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Excluir
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // ── Editar parcela ──────────────────────────────────────
    document.querySelectorAll('.btn-edit-installment').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id               = this.dataset.id;
            var number           = this.dataset.number;
            var amount           = this.dataset.amount;
            var dueDate          = this.dataset.dueDate;
            var paymentDate      = this.dataset.paymentDate;
            var status           = this.dataset.status;
            var installmentsCount = this.dataset.installmentsCount;

            document.getElementById('edit-installment-number').textContent = '#' + number;
            document.getElementById('edit-amount').value            = amount;
            document.getElementById('edit-due-date').value          = dueDate;
            document.getElementById('edit-payment-date').value      = paymentDate;
            document.getElementById('edit-status').value            = status;
            document.getElementById('edit-installments-count').value = installmentsCount;

            document.getElementById('editInstallmentForm').action =
                '/admin/faturas/{{ $invoice->id }}/installments/' + id;

            new bootstrap.Modal(document.getElementById('editInstallmentModal')).show();
        });
    });

    // ── Excluir parcela ─────────────────────────────────────
    document.querySelectorAll('.btn-delete-installment').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id     = this.dataset.id;
            var number = this.dataset.number;

            document.getElementById('delete-installment-number').textContent = number;

            document.getElementById('deleteInstallmentForm').action =
                '/admin/faturas/{{ $invoice->id }}/installments/' + id;

            new bootstrap.Modal(document.getElementById('deleteInstallmentModal')).show();
        });
    });
</script>
@endsection