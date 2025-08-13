<table class="table table-bordered">
    <thead>
        <tr>
            <th class="col-3">Nome</th>
            <th class="col-4">Descrição</th>
            <th class="col-2">Status</th>
            <th class="col-2">Tags</th>
            <th>Ação</th>
        </tr>
    </thead>
    <tbody>
        @forelse($projetos as $projeto)
            <tr>
                <td>{{ $projeto->nome }}</td>
                <td>{{ $projeto->descricao ?? 'Sem descrição' }}</td>
                <td>
                    <span class="badge {{ $projeto->status == 'concluido' ? 'bg-success' : 'bg-warning' }}">
                        {{ ucfirst($projeto->status) }}
                    </span>
                </td>
                <td>
                    @if($projeto->tags->isNotEmpty())
                        {{ $projeto->tags->pluck('name')->join(', ') }}
                    @else
                        <span class="text-muted fst-italic">Sem tags</span>
                    @endif
                </td>
                <td>
                    <div class="dropdown text-center"></div>
                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-cog"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li>
                                @can('Editar Projeto')
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('projetos.edit', $projeto->id) }}"><i class="bi bi-pencil-square text-warning me-2"></i> Editar</a></li>
                                @endcan
                            <li>
                                <form action="{{ route('projetos.destroy', $projeto->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    @can('Deletar Projeto')
                                    <button type="submit" class="dropdown-item d-flex align-items-center btn-delete"
                                        data-url="{{ route('projetos.destroy', $projeto->id) }}"
                                        data-nome="{{ $projeto->nome }}"
                                        data-descricao="{{ $projeto->descricao }}">
                                        <i class="bi bi-trash text-danger me-2"></i> Deletar
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
                <td colspan="4" class="text-center">Não há projetos 😢</td>
            </tr>
        @endforelse
    </tbody>
</table>

{!! $projetos->withQueryString()->links('pagination::bootstrap-5') !!}