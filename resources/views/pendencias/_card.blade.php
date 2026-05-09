@php
    $mesesPt = [1=>'Janeiro',2=>'Fevereiro',3=>'Março',4=>'Abril',5=>'Maio',6=>'Junho',7=>'Julho',8=>'Agosto',9=>'Setembro',10=>'Outubro',11=>'Novembro',12=>'Dezembro'];
    $referenceDate = $installment->due_date->copy()->subMonth();
    $referenceLabel = $mesesPt[(int)$referenceDate->format('n')] . '/' . $referenceDate->format('Y');

    $statusBadge = match($installment->status) {
        'paga'     => ['class' => 'bg-success',           'label' => 'PAGO'],
        'pendente' => ['class' => 'bg-warning text-dark', 'label' => 'PENDENTE'],
        'vencida'  => ['class' => 'bg-danger',            'label' => 'VENCIDA'],
        default    => ['class' => 'bg-secondary',         'label' => strtoupper($installment->status)],
    };
@endphp
<div class="card mb-3 shadow-sm border-0 rounded-4 pendencia-card">
    <div class="card-body">
        <div class="d-flex align-items-start">
            <div class="me-3">
                <div class="rounded-circle border border-2 border-success d-flex align-items-center justify-content-center"
                     style="width: 48px; height: 48px;">
                    <i class="bi bi-currency-dollar text-success fs-4"></i>
                </div>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <h5 class="mb-0 fw-bold">Mensalidade</h5>
                    <span class="badge {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
                </div>
                <p class="text-muted mb-2 small">Mensalidade referente a {{ $referenceLabel }}</p>
                <h4 class="mb-1 fw-bold">R$ {{ number_format($installment->amount, 2, ',', '.') }}</h4>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small">{{ $installment->due_date->format('d/m/Y') }}</span>
                    <div class="d-flex gap-2">
                        @if($installment->boleto_path)
                            <button type="button"
                                    class="btn btn-outline-danger btn-sm btn-preview-boleto"
                                    data-number="{{ $installment->installment_number }}"
                                    data-url="{{ asset('storage/' . $installment->boleto_path) }}">
                                <i class="bi bi-eye me-1"></i>Visualizar
                            </button>
                            <a href="{{ asset('storage/' . $installment->boleto_path) }}"
                               download
                               class="btn btn-danger btn-sm">
                                <i class="bi bi-download me-1"></i>Baixar
                            </a>
                        @else
                            <button type="button" class="btn btn-outline-secondary btn-sm" disabled title="Sem boleto disponível">
                                <i class="bi bi-eye me-1"></i>Visualizar
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" disabled title="Sem boleto disponível">
                                <i class="bi bi-download me-1"></i>Baixar
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
