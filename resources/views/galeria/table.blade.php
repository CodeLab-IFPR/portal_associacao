@forelse($midias as $midia)
    <tr id="midia-{{ $midia->id }}">
        <td>
            @if($midia->tipo == 'imagem')
                <img src="{{ asset($midia->caminho) }}" alt="{{ $midia->titulo }}" width="100px">
            @else
                @php
                    $videoId = '';
                    if (preg_match('/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/', $midia->caminho, $match)) {
                        $videoId = $match[7];
                    }
                @endphp
                <img src="https://img.youtube.com/vi/{{ $videoId }}/mqdefault.jpg" alt="{{ $midia->titulo }}" width="100px">
            @endif
        </td>
        <td>{{ $midia->titulo }}</td>
        <td>{{ $midia->descricao }}</td>
        <td>
            <div class="dropdown">
                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                    id="dropdownMenuButton{{ $midia->id }}" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="bi bi-gear"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $midia->id }}">
                    <li>
                        <a class="dropdown-item d-flex align-items-center"
                            href="{{ route('galeria.edit', $midia->id) }}">
                            <i class="bi bi-pencil-square text-warning me-2"></i> Editar
                        </a>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center btn-delete"
                            data-url="{{ route('galeria.destroy', $midia->id) }}"
                            data-titulo="{{ $midia->titulo }}">
                            <i class="bi bi-trash text-danger me-2"></i> Excluir
                        </button>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center">Não há mídias 😢</td>
    </tr>
@endforelse