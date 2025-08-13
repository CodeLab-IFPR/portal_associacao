@extends('layouts.admin')

<!-- Título -->
@section('title')
Certificados - Lista
@endsection
<!-- Título -->

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Certificados - Lista</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Certificados - Lista
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="d-grid gap-2 d-md-flex justify-content-md-end" style="margin-right: 10px;">
        <a class="btn btn-outline-success btn-sm" href="{{ route('certificados.create') }}">
            <i class="fa fa-plus"></i> Criar Certificado
        </a>
    </div>
    <div class="card-body">

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
        <div class="d-flex justify-content-center mb-4">
            <form id="search-form" class="d-flex" method="GET" action="{{ route('certificados.index') }}">
            <input id="search-input" class="form-control me-2" type="search" name="search" placeholder="Buscar Certificados"
                aria-label="Search">
            <button class="btn btn-outline-success" type="submit">
                <i class="bi bi-search"></i>
            </button>
            </form>
        </div>
        <div class="card-body">
            <div id="certificados-table-container">
                @include('certificados.table', ['certificados' => $certificados])
            </div>
    </div>

    </div>
</div>
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza de que deseja excluir este certificado? Esta ação não pode ser desfeita.</p>
                <div id="certificado-info">
                    <p><strong>Nome:</strong> <span id="certificado-nome"></span></p>
                    <p><strong>Descrição:</strong> <span id="certificado-descricao"></span></p>
                    <p><strong>Horas:</strong> <span id="certificado-horas"></span></p>
                    <p><strong>Data:</strong> <span id="certificado-data"></span></p>
                    <p><strong>Token:</strong> <span id="certificado-token"></span></p>
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
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#search-form').on('submit', function (e) {
            e.preventDefault();
            var query = $('input[name="search"]').val();
            $.ajax({
                url: "{{ route('certificados.index') }}",
                type: 'GET',
                data: { search: query },
                success: function (response) {
                    $('#certificados-table-container').html(response.table);
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    alert('Ocorreu um erro ao tentar buscar os certificados.');
                }
            });
        });

        $('body').on('click', '.btn-delete', function (e) {
            e.preventDefault();
            var url = $(this).data('url');
            var nome = $(this).data('nome');
            var descricao = $(this).data('descricao');
            var horas = $(this).data('horas');
            var data = $(this).data('data');
            var token = $(this).data('token');

            $('#certificado-nome').text(nome);
            $('#certificado-descricao').text(descricao);
            $('#certificado-horas').text(horas);
            $('#certificado-data').text(data);
            $('#certificado-token').text(token);

            $('#confirmDeleteButton').data('url', url);
            $('#confirmDeleteModal').modal('show');
        });

        $('#confirmDeleteButton').on('click', function () {
            var url = $(this).data('url');
            $.ajax({
                url: url,
                method: 'DELETE',
                success: function (response) {
                    $('#certificados-table-container').html(response.table);
                    $('#confirmDeleteModal').modal('hide');
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    alert('Ocorreu um erro ao tentar excluir o certificado.');
                }
            });
        });
    });
</script>
@endsection
