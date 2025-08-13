<table class="table table-bordered table-striped mt-4" id="parceiros-table">
    <thead>
        <tr>
            <th>Imagem</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Ação</th>
        </tr>
    </thead>

    <tbody>
        @forelse($parceiros as $parceiro)
            <tr>
                <td><a href="{{$parceiro->link}}"><img src="/imagens/parceiros/{{ $parceiro->imagem }}" alt="{{ $parceiro->alt }}" width="100px"></a></td>
                <td>{{ $parceiro->nome }}</td>
                <td>{{ $parceiro->email }}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                            id="dropdownMenuButton{{ $parceiro->id }}" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-gear"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $parceiro->id }}">
                            <li>
                                <a class="dropdown-item d-flex align-items-center"
                                    href="{{ route('parceiros.show', $parceiro->id) }}">
                                    <i class="bi bi-eye text-secondary me-2"></i> Visualizar
                                </a>
                            </li>
                            <li>
                                @can('Editar Parceiro')
                                <a class=" dropdown-item d-flex align-items-center"
                                    href="{{ route('parceiros.edit', $parceiro->id) }}">
                                    <i class="bi bi-pencil-square text-warning me-2"></i> Editar
                                </a>
                                @endcan
                            </li>
                            <li>
                                @can('Deletar Parceiro')
                                <a href="#" class="dropdown-item d-flex align-items-center btn-delete"
                                    data-url="{{ route('parceiros.destroy', $parceiro->id) }}"
                                    data-nome="{{ $parceiro->nome }}"
                                    data-email="{{ $parceiro->email }}">
                                    <i class="bi bi-trash text-danger me-2"></i> Deletar
                                </a>
                                @endcan
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">Não há parceiros 😢</td>
            </tr>
        @endforelse
    </tbody>
</table>

{!! $parceiros->withQueryString()->links('pagination::bootstrap-5') !!}