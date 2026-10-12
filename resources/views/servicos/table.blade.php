
<div class="table-responsive">
<table class="table table-bordered">
    <thead>
        <tr>
            <th class="col-4">Descrição</th>
            <th>Ação</th>
        </tr>
    </thead>
    <tbody>
        @forelse($servicos as $servico)
            <tr>
                <td>{{ $servico->descricao ?? 'Sem descrição' }}</td>
                <td>
                    <div class="dropdown text-center"></div>
                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-cog"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li>
                                @can('Editar Serviço')
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('servicos.edit', $servico->id) }}"><i class="bi bi-pencil-square text-warning me-2"></i> Editar</a></li>
                                @endcan
                            <li>
                                <form action="{{ route('servicos.destroy', $servico->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    @can('Deletar Serviço')
                                    <button type="submit" class="dropdown-item d-flex align-items-center btn-delete"
                                        data-url="{{ route('servicos.destroy', $servico->id) }}"
                                        data-descricao="{{ $servico->descricao }}">
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
                <td colspan="2" class="text-center">Não há serviços 😢</td>
            </tr>
        @endforelse
    </tbody>
</table>
</div>

{!! $servicos->withQueryString()->links('pagination::bootstrap-5') !!}