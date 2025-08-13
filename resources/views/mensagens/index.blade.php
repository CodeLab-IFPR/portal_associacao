@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.admin')

<!-- Título -->
@section('title')
Mensagens de Contato
@endsection
<!-- Título -->

@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Mensagens de Contato</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Mensagens de Contato
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container mt-4">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
            <button id="mark-read-selected" class="btn btn-primary btn-sm" title="Marcar Selecionadas como Lidas">
            <i class="bi bi-envelope-open"></i>
            </button>
            <button id="mark-unread-selected" class="btn btn-success btn-sm" title="Marcar Selecionadas como Não Lidas">
                <i class="bi bi-envelope"></i>
            </button>
            <button id="delete-selected" class="btn btn-danger btn-sm" title="Excluir Selecionadas">
                <i class="bi bi-trash"></i>
            </button>
            </div>
            <style>
                .clickable-cell {
                    cursor: pointer;
                    transition: background-color 0.3s;
                }
                .clickable-cell:hover {
                    background-color: #f0f0f0;
                }
            </style>
            <table class="table table-bordered table-striped mt-4" id="mensagens-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th></th>
                        <th>Assunto</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Mensagem</th>
                        <th>Status</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mensagens as $mensagem)
                        <tr>
                            <td><input type="checkbox" class="message-checkbox" data-id="{{ $mensagem->id }}"></td>
                            <td>
                                <i class="bi {{ $mensagem->read ? 'bi-envelope-open text-primary' : 'bi-envelope text-success' }} toggle-read" data-id="{{ $mensagem->id }}"></i>
                            </td>
                            <td class="clickable-cell" data-href="{{ route('mensagens.show', $mensagem->id) }}">{{ $mensagem->subject }}</td>
                            <td class="clickable-cell" data-href="{{ route('mensagens.show', $mensagem->id) }}">{{ $mensagem->name }}</td>
                            <td class="clickable-cell" data-href="{{ route('mensagens.show', $mensagem->id) }}">{{ $mensagem->email }}</td>
                            <td class="clickable-cell" data-href="{{ route('mensagens.show', $mensagem->id) }}">{{ Str::limit($mensagem->message, 50) }}</td>
                            <td>{{ $mensagem->read ? 'Lida' : 'Não Lida' }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                                        id="dropdownMenuButton{{ $mensagem->id }}" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="bi bi-gear"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $mensagem->id }}">
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center"
                                                href="{{ route('mensagens.show', $mensagem->id) }}">
                                                <i class="bi bi-eye text-secondary me-2"></i> Visualizar
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="dropdown-item d-flex align-items-center btn-mark-read"
                                                data-url="{{ route('mensagens.markRead', $mensagem->id) }}">
                                                <i class="bi bi-check-circle text-success me-2"></i> Marcar como Lida
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="dropdown-item d-flex align-items-center btn-mark-unread"
                                                data-url="{{ route('mensagens.markUnread', $mensagem->id) }}">
                                                <i class="bi bi-x-circle text-warning me-2"></i> Marcar como Não Lida
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="dropdown-item d-flex align-items-center btn-delete"
                                                data-url="{{ route('mensagens.destroy', $mensagem->id) }}">
                                                <i class="bi bi-trash text-danger me-2"></i> Deletar
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Não há mensagens 😢</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {!! $mensagens->withQueryString()->links('pagination::bootstrap-5') !!}
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

    $('#select-all').on('click', function() {
        $('.message-checkbox').prop('checked', this.checked);
    });

    $('body').on('click', '.toggle-read', function () {
        var id = $(this).data('id');
        var icon = $(this);
        $.ajax({
            url: '/admin/mensagens/' + id + '/toggleRead',
            method: 'POST',
            success: function (response) {
                icon.toggleClass('bi-envelope bi-envelope-open');
                location.reload();
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                alert('Ocorreu um erro ao tentar alterar o status da mensagem.');
            }
        });
    });

    $('#mark-read-selected').on('click', function () {
        var ids = $('.message-checkbox:checked').map(function () {
            return $(this).data('id');
        }).get();
        if (ids.length > 0) {
            $.ajax({
                url: '/admin/mensagens/markReadSelected',
                method: 'POST',
                data: { ids: ids },
                success: function (response) {
                    location.reload();
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    alert('Ocorreu um erro ao tentar marcar as mensagens como lidas.');
                }
            });
        }
    });

    $('#mark-unread-selected').on('click', function () {
        var ids = $('.message-checkbox:checked').map(function () {
            return $(this).data('id');
        }).get();
        if (ids.length > 0) {
            $.ajax({
                url: '/admin/mensagens/markUnreadSelected',
                method: 'POST',
                data: { ids: ids },
                success: function (response) {
                    location.reload();
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    alert('Ocorreu um erro ao tentar marcar as mensagens como não lidas.');
                }
            });
        }
    });

    $('#delete-selected').on('click', function () {
        var ids = $('.message-checkbox:checked').map(function () {
            return $(this).data('id');
        }).get();
        if (ids.length > 0) {
            $.ajax({
                url: '{{ route("mensagens.deleteSelected") }}',
                method: 'POST',
                data: { _method: 'DELETE', ids: ids },
                success: function (response) {
                    location.reload();
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    alert('Ocorreu um erro ao tentar excluir as mensagens.');
                }
            });
        }
    });

    $('body').on('click', '.btn-mark-read', function (e) {
        e.preventDefault();
        var url = $(this).data('url');
        $.ajax({
            url: url,
            method: 'POST',
            success: function (response) {
                location.reload();
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                alert('Ocorreu um erro ao tentar marcar a mensagem como lida.');
            }
        });
    });

    $('body').on('click', '.btn-mark-unread', function (e) {
        e.preventDefault();
        var url = $(this).data('url');
        $.ajax({
            url: url,
            method: 'POST',
            success: function (response) {
                location.reload();
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                alert('Ocorreu um erro ao tentar marcar a mensagem como não lida.');
            }
        });
    });

    $('body').on('click', '.btn-delete', function (e) {
        e.preventDefault();
        var url = $(this).data('url');
        $.ajax({
            url: url,
            method: 'DELETE',
            success: function (response) {
                location.reload();
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                alert('Ocorreu um erro ao tentar excluir a mensagem.');
            }
        });
    });

    // Make specific table cells clickable
    $('body').on('click', '.clickable-cell', function () {
        window.location = $(this).data('href');
    });
});
</script>
@endsection