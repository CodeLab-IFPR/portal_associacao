@extends('layouts.admin')

@section('title')
Permissões
@endsection

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Permissões</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Permissões</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container d-flex justify-content-center">
    <div class="w-75">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
            <a class="btn btn-outline-success btn-sm" href="{{ route('permissoes.create') }}"> <i
                    class="fa fa-plus"></i> Nova Permissão</a>
        </div>
        <br>
        <table class="table table-bordered" id="myTable">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Criado em</th>
                    <th>Atualizado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($permissoes as $permissao)
                    <tr>
                        <td>{{ $permissao->name }}</td>
                        <td>{{ $permissao->created_at->format('d M Y \à\s H:i:s') }}
                        </td>
                        <td>{{ $permissao->updated_at->format('d M Y \à\s H:i:s') }}
                        </td>
                        <td>
                            <div class="dropdown text-center">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                                    id="dropdownMenuButton{{ $permissao->id }}" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa fa-cog"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $permissao->id }}">
                                    <li>
                                        @can('Editar Permissão')
                                        <a class="dropdown-item"
                                            href="{{ route('permissoes.edit', $permissao->id) }}"><i
                                                class="fas fa-pen-to-square"></i> Editar</a>      
                                        @endcan
                                    </li>
                                    <li>
                                        <form
                                            action="{{ route('permissoes.destroy', $permissao->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            @can('Deletar Permissão')
                                            <button type="submit"
                                                class="dropdown-item danger"
                                                onclick="return confirm('Tem certeza que deseja deletar esta permissão?')">
                                                <i class="fas fa-trash"></i> Deletar
                                            </button>
                                            @endcan
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Não há permissoes 😢</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
    <div class="d-flex justify-content-center mt-4">
    {!! $permissoes->withQueryString()->links('pagination::bootstrap-5') !!}
</div>
@endsection