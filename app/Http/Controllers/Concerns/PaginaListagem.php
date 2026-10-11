<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

trait PaginaListagem
{
    /**
     * Pagina uma listagem com os links apontando para a tela da listagem.
     *
     * As exclusões via AJAX devolvem a tabela já atualizada; sem o withPath,
     * os links da paginação saem com a URL da exclusão (/recurso/{id}?page=2)
     * e, ao clicar, levam ao registro que acabou de ser apagado.
     *
     * @param  array  $manterNaUrl  parâmetros (busca, filtros) repetidos nos links
     */
    protected function paginarListagem(Builder $query, string $rotaIndex, int $porPagina, array $manterNaUrl = []): LengthAwarePaginator
    {
        $parametros = array_filter(request()->only($manterNaUrl), fn ($valor) => $valor !== null && $valor !== '');
        $pagina = max(1, (int) request()->input('page', 1));

        $itens = (clone $query)->paginate($porPagina, ['*'], 'page', $pagina);

        // Excluir o último item da última página deixaria a tabela vazia
        if ($itens->isEmpty() && $pagina > 1) {
            $itens = (clone $query)->paginate($porPagina, ['*'], 'page', $itens->lastPage());
        }

        return $itens->withPath(route($rotaIndex))->appends($parametros);
    }
}
