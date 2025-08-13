@extends('layouts.admin')

@section('title')
Lançamentos
@endsection

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Lançamentos</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Lançamentos</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container d-flex justify-content-center">
    <div class="w-75">
    @if(session('success'))
        <div id="alert" class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="alert-content">
                <strong>{{ session('success') }}</strong>
            </div>
            <div class="progress-bar-container">
                <div id="progress-bar" class="progress-bar"></div>
            </div>
        </div>
    @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                @can('Criar Projeto')
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filtrosModal">
                    <i class="fas fa-filter me-2"></i>Filtros
                </button>
                @endcan

                @if(request()->hasAny(['user_id', 'data_inicio', 'data_fim', 'certificado_status']))
                    <a href="{{ route('lancamentos.index') }}" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-times me-1"></i>Limpar Filtros
                    </a>
                @endif
            </div>
                        
        </div>

        <form method="POST" action="{{ route('lancamentos.generateCertificates') }}">
            @csrf
            <div id="lancamentos-table-container">
                @include('lancamentos.table', ['lancamentos' => $lancamentos])
            </div>
            @hasrole('Admin')
            <button type="submit" class="btn btn-outline-success">Gerar Certificados</button>
            @endhasrole
        </form>
    </div>
</div>

@can('Criar Projeto')
<div class="modal fade" id="filtrosModal" tabindex="-1" aria-labelledby="filtrosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filtrosModalLabel">
                    <i class="fas fa-filter me-2"></i>Filtros de Lançamentos
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('lancamentos.index') }}" id="filtros-form">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="user_id" class="form-label">
                                Aluno
                            </label>
                            <select name="user_id" id="user_id" class="form-select">
                                <option value="">Todos os alunos</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="certificado_status" class="form-label">
                                Status do Certificado
                            </label>
                            <select name="certificado_status" id="certificado_status" class="form-select">
                                <option value="">Todos</option>
                                <option value="1" {{ request('certificado_status') === '1' ? 'selected' : '' }}>Gerado</option>
                                <option value="0" {{ request('certificado_status') === '0' ? 'selected' : '' }}>Pendente</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="data_inicio" class="form-label">
                                Data de início
                            </label>
                            <input type="date" name="data_inicio" id="data_inicio" class="form-control" 
                                   value="{{ request('data_inicio') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="data_fim" class="form-label">
                                Data final
                            </label>
                            <input type="date" name="data_fim" id="data_fim" class="form-control" 
                                   value="{{ request('data_fim') }}">
                        </div>
                    </div>

                    <input type="hidden" name="order" value="{{ request('order', 'created_at') }}">
                    <input type="hidden" name="direction" value="{{ request('direction', 'desc') }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancelar
                    </button>
                    <a href="{{ route('lancamentos.index') }}" class="btn btn-outline-danger">
                        <i class="fas fa-trash me-1"></i>Limpar Filtros
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza de que deseja excluir este lançamento? Esta ação não pode ser desfeita.</p>
                <div id="lancamento-info">
                    <p><strong>Projeto:</strong> <span id="lancamento-projeto"></span></p>
                    <p><strong>Serviço:</strong> <span id="lancamento-servico"></span></p>
                    <p><strong>Nome:</strong> <span id="lancamento-nome"></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
                <button type="button" id="confirmDeleteButton" class="btn btn-danger">Excluir</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('select-all').onclick = function () {
        var checkboxes = document.getElementsByName('lancamentos[]');
        for (var checkbox of checkboxes) {
            if (!checkbox.disabled) {
                checkbox.checked = this.checked;
            }
        }
    }

    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Validação de datas no modal
        $('#data_inicio, #data_fim').on('change', function() {
            var dataInicio = $('#data_inicio').val();
            var dataFim = $('#data_fim').val();
            
            if (dataInicio && dataFim && dataInicio > dataFim) {
                alert('A data de início não pode ser posterior à data fim.');
                $(this).val('');
                return false;
            }
        });

        // Fechar modal após submit
        $('#filtros-form').on('submit', function() {
            $('#filtrosModal').modal('hide');
        });

        $('body').on('click', '.btn-delete', function (e) {
            e.preventDefault();
            var url = $(this).data('url');
            var projeto = $(this).data('projeto');
            var servico = $(this).data('servico');
            var nome = $(this).data('nome');

            $('#lancamento-projeto').text(projeto);
            $('#lancamento-servico').text(servico);
            $('#lancamento-nome').text(nome);

            $('#confirmDeleteButton').data('url', url);
            $('#confirmDeleteModal').modal('show');
        });

        $('#confirmDeleteButton').on('click', function () {
            var url = $(this).data('url');
            
            var currentParams = new URLSearchParams(window.location.search);
            var urlWithParams = url;
            
            if (currentParams.toString()) {
                urlWithParams += (url.includes('?') ? '&' : '?') + currentParams.toString();
            }
            
            $.ajax({
                url: urlWithParams,
                method: 'DELETE',
                success: function (response) {
                    if (response.table) {
                        $('#lancamentos-table-container').html(response.table);
                        $('#confirmDeleteModal').modal('hide');
                    } else {
                        location.reload();
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    alert('Ocorreu um erro ao tentar excluir o lançamento.');
                }
            });
        });
    });
</script>

<style>
.badge {
    font-size: 0.75rem;
}

.btn {
    transition: all 0.2s ease-in-out;
}

.btn:hover {
    transform: translateY(-1px);
}

.modal-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.badge.bg-danger {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}
</style>
@endsection