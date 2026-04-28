@extends('layouts.admin')

@section('title')
Faturas
@endsection

@section('content')

{{-- Tom Select CSS --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <div class="d-flex align-items-center gap-3">
                    <h3 class="mb-0">Nova Fatura</h3>
                    <div class="alert alert-info py-1 px-2 mb-0 small" id="total-summary" style="display:none;">
                        <i class="bi bi-info-circle me-1"></i>
                        Total da fatura: <strong id="total-value">R$ 0,00</strong>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">Faturas</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Criar</li>
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
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-receipt me-2"></i>
                            Cadastrar Faturas
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('invoices.store') }}" method="POST">
                            @csrf

                            {{-- Associados --}}
                            <div class="mb-3">
                                <label for="user_ids" class="form-label"><strong>Associados*</strong></label>
                                <select name="user_ids[]" id="user_ids" multiple required>
                                    @foreach ($members as $member)
                                        <option value="{{ $member->id }}"
                                            {{ is_array(old('user_ids')) && in_array($member->id, old('user_ids')) ? 'selected' : '' }}>
                                            {{ $member->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_ids')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @error('user_ids.*')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row">
                                {{-- Valor da Parcela --}}
                                <div class="col-md-6 mb-3">
                                    <label for="installment_amount" class="form-label"><strong>Valor da Parcela (R$)*</strong></label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" name="installment_amount" id="installment_amount"
                                            class="form-control" min="0.01" step="0.01"
                                            value="{{ old('installment_amount') }}"
                                            placeholder="0,00" required>
                                    </div>
                                    @error('installment_amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Quantidade de Parcelas --}}
                                <div class="col-md-6 mb-3">
                                    <label for="installments_count" class="form-label"><strong>Quantidade de Parcelas*</strong></label>
                                    <input type="number" name="installments_count" id="installments_count"
                                        class="form-control" min="1" max="60"
                                        value="{{ old('installments_count') }}"
                                        placeholder="Ex: 12" required>
                                    @error('installments_count')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                {{-- Data do Primeiro Vencimento --}}
                                <div class="col-md-6 mb-3">
                                    <label for="first_due_date" class="form-label"><strong>Data do Primeiro Vencimento*</strong></label>
                                    <input type="date" name="first_due_date" id="first_due_date"
                                        class="form-control"
                                        value="{{ old('first_due_date') }}"
                                        required>
                                    @error('first_due_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Periodicidade --}}
                                <div class="col-md-6 mb-3">
                                    <label for="periodicity" class="form-label"><strong>Periodicidade*</strong></label>
                                    <select name="periodicity" id="periodicity" class="form-select" required>
                                        <option value="" disabled {{ old('periodicity') ? '' : 'selected' }}>Selecione...</option>
                                        <option value="semanal"     {{ old('periodicity') == 'semanal'     ? 'selected' : '' }}>Semanal</option>
                                        <option value="quinzenal"   {{ old('periodicity') == 'quinzenal'   ? 'selected' : '' }}>Quinzenal</option>
                                        <option value="mensal"      {{ old('periodicity') == 'mensal'      ? 'selected' : '' }}>Mensal</option>
                                        <option value="bimestral"   {{ old('periodicity') == 'bimestral'   ? 'selected' : '' }}>Bimestral</option>
                                        <option value="trimestral"  {{ old('periodicity') == 'trimestral'  ? 'selected' : '' }}>Trimestral</option>
                                        <option value="semestral"   {{ old('periodicity') == 'semestral'   ? 'selected' : '' }}>Semestral</option>
                                        <option value="anual"       {{ old('periodicity') == 'anual'       ? 'selected' : '' }}>Anual</option>
                                    </select>
                                    @error('periodicity')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Observações --}}
                            <div class="mb-3">
                                <label for="notes" class="form-label"><strong>Observações</strong></label>
                                <textarea name="notes" id="notes" class="form-control" rows="3"
                                    style="resize: none;" maxlength="500"
                                    placeholder="Observações adicionais sobre a fatura...">{{ old('notes') }}</textarea>
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

{{-- Tom Select JS --}}
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<script>
    // Multiselect com tags e busca
    new TomSelect('#user_ids', {
        plugins: ['remove_button'],
        placeholder: 'Selecione um ou mais associados...',
        maxOptions: null,
    });

    // Cálculo do total em tempo real
    function updateTotal() {
        var amount = parseFloat(document.getElementById('installment_amount').value) || 0;
        var count  = parseInt(document.getElementById('installments_count').value)  || 0;
        var total  = amount * count;

        var summaryEl = document.getElementById('total-summary');
        var valueEl   = document.getElementById('total-value');

        if (amount > 0 && count > 0) {
            valueEl.textContent = 'R$ ' + total.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            summaryEl.style.display = 'block';
        } else {
            summaryEl.style.display = 'none';
        }
    }

    document.getElementById('installment_amount').addEventListener('input', updateTotal);
    document.getElementById('installments_count').addEventListener('input', updateTotal);

    // Abrir datepicker ao focar
    (function setupDatePickerAutoOpen() {
        const el = document.getElementById('first_due_date');
        if (!el) return;
        const tryShowPicker = () => { if (typeof el.showPicker === 'function') el.showPicker(); };
        el.addEventListener('focus', tryShowPicker);
        el.addEventListener('click', tryShowPicker);
    })();
</script>
@endsection