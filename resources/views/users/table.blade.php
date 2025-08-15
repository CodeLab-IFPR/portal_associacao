<table class="table table-bordered table-striped mt-4" id="users-table">
    <thead>
        <tr>
            <th>Imagem</th>
            <th>Nome</th>
            <th>Cpf</th>
            <th>Email</th>
            <th>Ativo</th>
            <th>Cargo</th>
            <th>Função</th>
            <th>Ação</th>
        </tr>
    </thead>

    <tbody>
        @forelse($users as $user)
            <tr>
                <td><img src="/imagens/users/{{ $user->imagem }}" alt="{{ $user->alt }}" width="80px"></td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->cpf }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->ativo ? 'Sim' : 'Não' }}</td>
                <td>{{ $user->cargo }}</td>
                <td>
                    @if (!empty($user->getRoleNames()))
                        @foreach ($user->getRoleNames() as $role)
                            <span class="badge bg-primary mx-1">{{ $role }}</span>
                        @endforeach
                        
                    @endif
                </td>
                <td>
                    <div class="d-flex flex-column gap-1" role="group" aria-label="Ações do usuário">
                        <!-- Primeira linha -->
                        <div class="d-flex gap-1 justify-content-center">
                            <!-- Visualizar -->
                            <a href="{{ route('users.show', $user->id) }}" 
                               class="btn btn-outline-secondary btn-sm px-2" 
                               title="Visualizar">
                                <i class="bi bi-eye"></i>
                            </a>

                            <!-- Documentos -->
                            @can('Visualizar Documento')
                            <a href="{{ route('documentos.usuario', $user->id) }}" 
                               class="btn btn-outline-info btn-sm px-2" 
                               title="Documentos">
                                <i class="bi bi-file-earmark-text"></i>
                            </a>
                            @endcan
                        </div>

                        <!-- Segunda linha -->
                        <div class="d-flex gap-1 justify-content-center">
                            <!-- Editar -->
                            @can('Editar Membro')
                            <a href="{{ route('users.edit', $user->id) }}" 
                               class="btn btn-outline-warning btn-sm px-2" 
                               title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            @endcan

                            <!-- Deletar -->
                            @can('Deletar Membro')
                            <button type="button" 
                                    class="btn btn-outline-danger btn-sm px-2 btn-delete" 
                                    title="Deletar"
                                    data-url="{{ route('users.destroy', $user->id) }}"
                                    data-name="{{ $user->name }}"
                                    data-cpf="{{ $user->cpf }}"
                                    data-cargo="{{ $user->cargo }}"
                                    data-imagem="/imagens/users/{{ $user->imagem }}"
                                    data-alt="{{ $user->alt }}">
                                <i class="bi bi-trash"></i>
                            </button>
                            @endcan
                        </div>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a class="btn btn-outline-success btn-sm"
                            href="{{ route('users.create') }}">
                            <i class="fa fa-plus"></i> Adicionar Membro
                        </a>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{!! $users->withQueryString()->links('pagination::bootstrap-5') !!}