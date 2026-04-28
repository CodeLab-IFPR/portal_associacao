@extends('layouts.admin')

@section('title')
Faturas
@endsection

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <div class="d-flex align-items-center gap-3">
                    <h3 class="mb-0">Editar Fatura</h3>
                    <div class="alert alert-warning py-1 px-2 mb-0 small">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <strong>Atenção:</strong> Apenas as observações são editáveis.
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">Faturas</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Atenção!</strong> Corrija os erros abaixo:
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-receipt me-2"></i>
                            Fatura #{{ $invoice->id }}
                            <span class="badge ms-2
                                @if($invoice->status === 'pago') bg-success
                                @elseif($invoice->status === 'pendente') bg-warning text-dark
                                @elseif($invoice->status === 'vencido') bg-danger
                                @else bg-secondary
                                @endif">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </h5>
                    </div>
                    <div class="card-body">

                        <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Associado (somente leitura) --}}
                            <div class="mb-3">
                                <label class="form-label"><strong>Associado</strong></label>
                                <input type="text" class="form-control" value="{{ $invoice->user->name }}" disabled>
                            </div>

                            <div class="row">
                                {{-- Valor da Parcela (somente leitura) --}}
                                <div class="col-md-4 mb-3">
                                    <label class="form-label"><strong>Valor da Parcela</strong></label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="text" class="form-control"
                                            value="{{ number_format($invoice->installments->first()?->amount ?? 0, 2, ',', '.') }}"
                                            disabled>
                                    </div>
                                </div>

                                {{-- Quantidade de Parcelas (somente leitura) --}}
                                <div class="col-md-4 mb-3">
                                    <label class="form-label"><strong>Parcelas</strong></label>
                                    <input type="text" class="form-control"
                                        value="{{ $invoice->installments_count }}x"
                                        disabled>
                                </div>

                                {{-- Total (somente leitura) --}}
                                <div class="col-md-4 mb-3">
                                    <label class="form-label"><strong>Total</strong></label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="text" class="form-control"
                                            value="{{ number_format($invoice->total_amount, 2, ',', '.') }}"
                                            disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                {{-- Primeiro Vencimento (somente leitura) --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><strong>Primeiro Vencimento</strong></label>
                                    <input type="text" class="form-control"
                                        value="{{ \Carbon\Carbon::parse($invoice->first_due_date)->format('d/m/Y') }}"
                                        disabled>
                                </div>

                                {{-- Periodicidade (somente leitura) --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><strong>Periodicidade</strong></label>
                                    <input type="text" class="form-control"
                                        value="{{ ucfirst($invoice->periodicity) }}"
                                        disabled>
                                </div>
                            </div>

                            {{-- Observações (editável) --}}
                            <div class="mb-3">
                                <label for="notes" class="form-label"><strong>Observações</strong></label>
                                <textarea name="notes" id="notes" class="form-control" rows="3"
                                    style="resize: none;" maxlength="500"
                                    placeholder="Observações adicionais sobre a fatura...">{{ old('notes', $invoice->notes) }}</textarea>
                                @error('notes')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('invoices.index') }}" class="btn btn-outline-danger">
                                    <i class="bi bi-arrow-left me-1"></i>Voltar
                                </a>
                                <button type="submit" class="btn btn-outline-success">
                                    <i class="bi bi-check-lg me-1"></i>Salvar
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection