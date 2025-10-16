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

<div class="container pt-2 d-flex justify-content-center">
    <div class="card-body p-4 p-lg-5 rounded-4 shadow-sm bg-white" style="max-width: 1200px; width: 100%;">
        <header class="text-center mb-4">
            <h2 class="fs-4 fw-medium mb-3">{{ __('Gerenciamento de Galeria') }}</h2>
            <p class="text-muted">{{ __("Visualize e gerencie todas as mídias da galeria.") }}</p>
        </header>

        <!-- Seção de Ações -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-tools"></i> Ações</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div class="d-flex justify-content-center justify-content-md-start">
                        <!-- A busca será integrada aqui pelo DataTable -->
                        <div id="galeria-search-container"></div>
                    </div>
                    <div>
                        <a class="btn btn-success" href="{{ route('galeria.create') }}" aria-label="Adicionar Mídia">
                            <i class="fa fa-plus" aria-hidden="true"></i> Adicionar Mídia
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção da Tabela de Galeria -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-images"></i> Lista de Mídias</h5>
            </div>
            <div class="card-body p-0">
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
        </div>
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
            "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6">>rtip', // Remove o campo de busca padrão
        });

        // Criar busca customizada na seção de ações
        var customSearchInput = $('<input type="search" class="form-control me-2" placeholder="Buscar mídias" style="width: 250px;">');
        var searchButton = $('<button class="btn btn-outline-primary" type="button"><i class="bi bi-search"></i> Buscar</button>');
        
        var searchForm = $('<div class="d-flex"></div>').append(customSearchInput).append(searchButton);
        $('#galeria-search-container').append(searchForm);
        
        // Funcionalidade de busca customizada
        customSearchInput.on('keyup search input', function() {
            var searchValue = this.value;
            table.search(searchValue).draw();
        });
        
        // Busca também funciona ao pressionar Enter
        customSearchInput.on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                var searchValue = this.value;
                table.search(searchValue).draw();
            }
        });
        
        searchButton.on('click', function() {
            var searchValue = customSearchInput.val();
            table.search(searchValue).draw();
        });

        // Limpar busca quando o campo for limpo
        customSearchInput.on('search', function() {
            if (this.value === '') {
                table.search('').draw();
            }
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
            var button = $(this);
            
            // Desabilitar o botão para evitar cliques duplos
            button.prop('disabled', true).text('Excluindo...');
            
            $.ajax({
                url: url,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        $('#confirmDeleteModal').modal('hide');
                        // Recarregar a página ou atualizar a tabela
                        location.reload();
                    } else {
                        alert('Erro ao excluir a mídia: ' + (response.message || 'Erro desconhecido'));
                    }
                },
                error: function (xhr) {
                    console.error('Erro AJAX:', xhr);
                    var errorMessage = 'Ocorreu um erro ao tentar excluir a mídia.';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        errorMessage = 'Erro: ' + xhr.responseText;
                    }
                    
                    alert(errorMessage);
                },
                complete: function() {
                    // Reabilitar o botão
                    button.prop('disabled', false).text('Excluir');
                }
            });
        });
    });
</script>

@endsection
