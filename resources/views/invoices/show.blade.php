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
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-3">Nenhuma parcela encontrada.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Ações --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('invoices.index') }}" class="btn btn-outline-danger">
                        <i class="bi bi-arrow-left me-1"></i>Voltar
                    </a>
                    <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-outline-warning">
                        <i class="bi bi-pencil-square me-1"></i>Editar
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection