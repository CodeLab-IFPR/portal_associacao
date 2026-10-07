@php
    $isAdmin = $isAdmin ?? (auth()->user()->hasRole('admin') || auth()->user()->hasRole('Admin'));
    $referenceDate = $installment->due_date->copy()->subMonth();

    $referenceLabel = [
        1=>'Janeiro',2=>'Fevereiro',3=>'Março',4=>'Abril',5=>'Maio',6=>'Junho',7=>'Julho',8=>'Agosto',9=>'Setembro',10=>'Outubro',11=>'Novembro',12=>'Dezembro'
    ][(int) $referenceDate->format('n')] . '/' . $referenceDate->format('Y');

    $status = $installment->effective_status;
    $statusMeta = match($status) {
        'paga'     => ['badge' => 'bg-success', 'label' => 'PAGO', 'bar' => 'success'],
        'vencida'  => ['badge' => 'bg-danger', 'label' => 'VENCIDA', 'bar' => 'danger'],
        default    => ['badge' => 'bg-warning text-dark', 'label' => 'PENDENTE', 'bar' => 'warning'],
    };

    $invoice = $installment->invoice;
    $canSettle = $isAdmin && $status !== 'paga';
    $canReceipt = $status === 'paga';
@endphp

<div class="card mb-3 shadow-sm border-0 rounded-4 pendencia-card border-start border-5 border-{{ $statusMeta['bar'] }}">
    <div class="card-body p-3 p-md-4">
        <div class="d-flex flex-column flex-md-row gap-3">
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <h5 class="mb-0 fw-bold">{{ $invoice->user->name }}</h5>
                            <span class="badge {{ $statusMeta['badge'] }}">{{ $statusMeta['label'] }}</span>
                        </div>
                        <div class="text-muted small mt-1">CPF: {{ $invoice->user->cpf ?? 'Não informado' }} · Fatura #{{ $invoice->id }} · Parcela #{{ $installment->installment_number }}</div>
                    </div>
                    <div class="text-end">
                        <div class="text-muted small">Valor</div>
                        <div class="fs-4 fw-bold">R$ {{ number_format($installment->amount, 2, ',', '.') }}</div>
                    </div>
                </div>

                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <div class="text-muted small">Referência</div>
                        <div class="fw-semibold">Mensalidade ref. {{ $referenceLabel }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Vencimento</div>
                        <div class="fw-semibold">{{ $installment->due_date->format('d/m/Y') }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Pago em</div>
                        <div class="fw-semibold">{{ $installment->payment_date ? $installment->payment_date->format('d/m/Y') : '—' }}</div>
                    </div>
                </div>

                @if($invoice->notes)
                    <div class="mt-3 small text-muted">
                        <strong class="text-dark">Observações:</strong> {{ $invoice->notes }}
                    </div>
                @endif

                <div class="d-flex flex-wrap gap-2 mt-3">
                    @if($installment->boleto_path)
                        <a href="{{ asset('storage/' . $installment->boleto_path) }}"
                           class="btn btn-outline-primary btn-sm px-3"
                           download>
                            <i class="bi bi-download me-1"></i>Baixar boleto
                        </a>
                    @else
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3" disabled>
                            <i class="bi bi-download me-1"></i>Baixar boleto
                        </button>
                    @endif

                    @if($canSettle)
                        <form method="POST"
                              action="{{ route('pendencias.installments.settle', $installment->id) }}"
                              class="d-inline js-settle-form"
                              data-user-name="{{ $invoice->user->name }}"
                              data-installment-number="{{ $installment->installment_number }}"
                              data-amount="R$ {{ number_format($installment->amount, 2, ',', '.') }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm px-3">
                                <i class="bi bi-check2-circle me-1"></i>Dar baixa
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('invoices.show', $invoice->id) }}"
                       class="btn btn-outline-warning btn-sm px-3">
                        <i class="bi bi-pencil-square me-1"></i>Editar
                    </a>

                    @if($canReceipt)
                        <button type="button"
                                class="btn btn-outline-success btn-sm px-3 btn-view-proof"
                                data-number="{{ $installment->installment_number }}"
                                data-user-name="{{ $invoice->user->name }}"
                                data-user-cpf="CPF: {{ $invoice->user->cpf ?? 'Não informado' }}"
                                data-amount="R$ {{ number_format($installment->amount, 2, ',', '.') }}"
                                data-due-date="{{ $installment->due_date->format('d/m/Y') }}"
                                data-payment-date="{{ $installment->payment_date ? $installment->payment_date->format('d/m/Y') : '—' }}">
                            <i class="bi bi-receipt me-1"></i>Ver comprovante
                        </button>
                    @else
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3" disabled>
                            <i class="bi bi-receipt me-1"></i>Ver comprovante
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
