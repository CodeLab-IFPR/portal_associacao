@extends('layouts.admin')

@section('title')
Galeria Admin
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Galeria - Lista</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Galeria - Lista
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="d-grid gap-2 d-md-flex justify-content-md-end" style="margin-right: 10px;">
        <a class="btn btn-outline-success btn-sm" href="{{ route('galeria.create') }}" aria-label="Adicionar Mídia">
            <i class="fa fa-plus" aria-hidden="true"></i> Adicionar Mídia
        </a>
    </div>
    <br>
    <div id="galeria-table-container">
        <table id="galeria-datatable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Título</th>
                    <th>Descrição</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @include('galeria.table', ['midias' => $midias])
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza de que deseja excluir esta mídia? Esta ação não pode ser desfeita.</p>
                <div id="midia-info">
                    <p><strong>Título:</strong> <span id="midia-titulo"></span></p>
                    <img id="midia-imagem" src="" alt="Imagem da Mídia" style="width: 100%; height: auto; object-fit: cover;">
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
        // Configurar o token CSRF para todas as requisições AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#galeria-datatable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json',
                "decimal": "",
                "emptyTable": "Nenhum dado disponível na tabela",
                "info": "Mostrando _START_ até _END_ de _TOTAL_ registros",
                "infoEmpty": "Mostrando 0 até 0 de 0 registros",
                "infoFiltered": "(filtrado de _MAX_ registros no total)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ registros",
                "loadingRecords": "Carregando...",
                "processing": "Processando...",
                "search": "Buscar:",
                "zeroRecords": "Nenhum registro encontrado",
                "paginate": {
                    "first": "Primeiro",
                    "last": "Último",
                    "next": "Próximo",
                    "previous": "Anterior"
                },
                "aria": {
                    "sortAscending": ": ativar para ordenar a coluna em ordem crescente",
                    "sortDescending": ": ativar para ordenar a coluna em ordem decrescente"
                }
            },
            "pageLength": 10,
            "responsive": true,
            "order": [[1, 'asc']], // Ordenar pela coluna título
        });

        $('body').on('click', '.btn-delete', function (e) {
            e.preventDefault();
            var url = $(this).data('url');
            var titulo = $(this).data('titulo');
            var imagem = $(this).closest('tr').find('img').attr('src');

            $('#midia-titulo').text(titulo);
            $('#midia-imagem').attr('src', imagem);

            $('#confirmDeleteButton').data('url', url);
            $('#confirmDeleteModal').modal('show');
        });

        $('#confirmDeleteButton').on('click', function () {
            var url = $(this).data('url');
            $.ajax({
                url: url,
                method: 'DELETE',
                success: function (response) {
                    if (response.success) {
                        $('#confirmDeleteModal').modal('hide');
                        var table = $('#galeria-datatable').DataTable();
                        table.row('#midia-' + response.id).remove().draw(false);
                    } else {
                        alert('Erro ao excluir a mídia.');
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    alert('Ocorreu um erro ao tentar excluir a mídia.');
                }
            });
        });
    });
</script>

@endsection
