<table class="table table-bordered">
    <thead>
        <tr>
            <th class="col-3">Tag</th>
            <th class="col-2">Ação</th>
        </tr>
    </thead>
    <tbody>
        @forelse($tags as $tag)
            <tr>
                <td>{{ $tag->name }}</td>
                <td>
                    <div class="dropdown text-center">
                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-cog"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('tags.edit', $tag->id) }}">
                                    <i class="bi bi-pencil-square text-warning me-2"></i> Editar
                                </a>
                            </li>
                            <li>
                                <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="dropdown-item d-flex align-items-center btn-delete" data-url="{{ route('tags.destroy', $tag->id) }}" data-name="{{ $tag->name }}">
                                        <i class="bi bi-trash text-danger me-2"></i> Deletar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="2" class="text-center">Não há tags 😢</td>
            </tr>
        @endforelse
    </tbody>
</table>