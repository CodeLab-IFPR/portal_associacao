@extends('layouts.admin')

<!-- Título -->
@section('title')
Notícias - Lista
@endsection
<!-- Título -->


@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Notícias - Lista</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Notícias</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="d-grid gap-2 d-md-flex justify-content-md-end" style="margin-right: 10px;">
        <a class="btn btn-outline-success btn-sm" href="{{ route('noticias.create') }}">
            <i class="fa fa-plus"></i> Adicionar Notícia
        </a>
    </div>

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
        <form id="search-form" class="d-flex" method="GET" action="{{ route('noticias.index') }}">
            <input id="search-input" class="form-control me-2" type="search" name="search" placeholder="Buscar noticias" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    <div class="card-body">
        <div id="noticias-table-container">
            @include('noticias.table', ['noticias' => $noticias])
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
                <p>Tem certeza de que deseja excluir esta notícia? Esta ação não pode ser desfeita.</p>
                <div id="noticia-info">
                    <p><strong>Título:</strong> <span id="noticia-titulo"></span></p>
                    <p><strong>Autor:</strong> <span id="noticia-autor"></span></p>
                    <p><strong>Categoria:</strong> <span id="noticia-categoria"></span></p>
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
            var query = $('#search-input').val();
            $.ajax({
                url: "{{ route('noticias.index') }}",
                type: 'GET',
                data: { search: query },
                success: function (response) {
                    $('#noticias-table-container').html(response.table);
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    alert('Ocorreu um erro ao tentar buscar os noticias.');
                }
            });
        });

        $('body').on('click', '.btn-delete', function (e) {
            e.preventDefault();
            var url = $(this).data('url');
            var titulo = $(this).data('titulo');
            var autor = $(this).data('autor');
            var categoria = $(this).data('categoria');
            


            $('#noticia-titulo').text(titulo);
            $('#noticia-autor').text(autor);
            $('#noticia-categoria').text(categoria);


            $('#confirmDeleteButton').data('url', url);
            $('#confirmDeleteModal').modal('show');
        });

        $('#confirmDeleteButton').on('click', function () {
            var url = $(this).data('url');
            $.ajax({
                url: url,
                method: 'DELETE',
                success: function (response) {
                    if (response.table) {
                        $('#noticias-table-container').html(response.table);
                        $('#confirmDeleteModal').modal('hide');
                    } else {
                        location.reload();
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    alert('Ocorreu um erro ao tentar excluir o noticia.');
                }
            });
        });
    });
</script>
@endsection