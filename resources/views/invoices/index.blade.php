@extends('layouts.admin')

@section('title')
Faturas
@endsection

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Faturas</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Faturas</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        @if(session('success'))
            <div id="alert" class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session('success') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>{{ session('error') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('invoices.create') }}" class="btn btn-outline-primary">
                <i class="bi bi-plus-lg me-1"></i>Nova Fatura
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-list-ul me-2"></i>Lista de Faturas
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Associado</th>
                                <th scope="col">Total</th>
                                <th scope="col">Parcelas</th>
                                <th scope="col">Periodicidade</th>
                                <th scope="col">1º Vencimento</th>
                                <th scope="col">Status</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->id }}</td>
                                    <td>{{ $invoice->user->name }}</td>
                                    <td>R$ {{ number_format($invoice->total_amount, 2, ',', '.') }}</td>
                                    <td>{{ $invoice->installments_count }}x</td>
                                    <td>{{ ucfirst($invoice->periodicity) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($invoice->first_due_date)->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge
                                            @if($invoice->status === 'pago') bg-success
                                            @elseif($invoice->status === 'pendente') bg-warning text-dark
                                            @elseif($invoice->status === 'vencido') bg-danger
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="dropdown text-center">
                                            <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-cog"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center"
                                                        href="{{ route('invoices.show', $invoice->id) }}">
                                                        <i class="bi bi-eye text-info me-2"></i> Visualizar
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center"
                                                        href="{{ route('invoices.edit', $invoice->id) }}">
                                                        <i class="bi bi-pencil-square text-warning me-2"></i> Editar
                                                    </a>
                                                </li>
                                                <li>
                                                    <button type="button"
                                                        class="dropdown-item d-flex align-items-center btn-delete"
                                                        data-id="{{ $invoice->id }}"
                                                        data-nome="{{ $invoice->user->name }}"
                                                        data-total="R$ {{ number_format($invoice->total_amount, 2, ',', '.') }}">
                                                        <i class="bi bi-trash text-danger me-2"></i> Excluir
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">Nenhuma fatura encontrada.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($invoices->hasPages())
                <div class="card-footer">
                    {!! $invoices->links('pagination::bootstrap-5') !!}
                </div>
            @endif
        </div>

    </div>
</div>

{{-- Modal de Confirmação de Exclusão --}}
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza de que deseja excluir esta fatura? Esta ação não pode ser desfeita.</p>
                <div id="invoice-info">
                    <p><strong>Associado:</strong> <span id="invoice-nome"></span></p>
                    <p><strong>Total:</strong> <span id="invoice-total"></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.btn-delete').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id    = this.dataset.id;
            var nome  = this.dataset.nome;
            var total = this.dataset.total;

            document.getElementById('invoice-nome').textContent  = nome;
            document.getElementById('invoice-total').textContent = total;

            var form = document.getElementById('deleteForm');
            form.action = '/admin/faturas/' + id;

            var modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            modal.show();
        });
    });
</script>
@endsection