<?php

use Illuminate\Support\Facades\DB;

/*
| Excluir pelo botão (AJAX) devolve a tabela já atualizada. Os links da
| paginação dessa tabela precisam apontar para a listagem: antes eles saíam
| com a URL da exclusão (/admin/users/{id}?page=2) e, ao clicar, levavam
| ao registro que acabou de ser apagado.
*/

beforeEach(function () {
    $this->d = criarDadosBase();
    $this->actingAs($this->d['admin']);
});

/** Cria $n registros extras do módulo e devolve os ids criados. */
function criarRegistros(string $modulo, int $n, array $d): array
{
    $agora = now();
    $ids = [];

    foreach (range(1, $n) as $i) {
        $ids[] = match ($modulo) {
            'users' => \App\Models\User::factory()->create(['name' => "Pessoa $i"])->id,
            'noticias' => DB::table('noticias')->insertGetId(['titulo' => "Notícia $i", 'autor' => 'Admin', 'conteudo' => 'Conteúdo da notícia', 'categoria' => 'Geral', 'alt' => 'x', 'imagem' => 'x.jpg', 'created_at' => $agora, 'updated_at' => $agora]),
            'parceiros' => DB::table('parceiros')->insertGetId(['nome' => "Parceiro $i", 'email' => "p$i@example.com", 'link' => 'https://example.com', 'imagem' => 'x.jpg', 'alt' => 'Logo', 'created_at' => $agora, 'updated_at' => $agora]),
            'projetos' => DB::table('projetos')->insertGetId(['nome' => "Projeto $i", 'descricao' => 'Descrição', 'created_at' => $agora, 'updated_at' => $agora]),
            'servicos' => DB::table('servicos')->insertGetId(['descricao' => "Serviço $i", 'created_at' => $agora, 'updated_at' => $agora]),
            'certificados' => DB::table('certificados')->insertGetId(['user_id' => $d['membro']->id, 'token' => \Illuminate\Support\Str::random(10), 'descricao' => "Curso $i", 'horas' => 2, 'data' => $agora->toDateString(), 'created_at' => $agora, 'updated_at' => $agora]),
            'lancamentos' => DB::table('lancamento_servicos')->insertGetId(['projeto_id' => $d['ids']['projeto'], 'servico_id' => $d['ids']['servico'], 'user_id' => $d['membro']->id, 'data_inicio' => $agora->toDateString(), 'data_final' => $agora->toDateString(), 'horas_trabalhadas' => $i, 'link' => 'https://example.com', 'created_at' => $agora, 'updated_at' => $agora]),
        };
    }

    return $ids;
}

it('depois de excluir pelo botão, a paginação da tabela continua funcionando', function (string $modulo, int $porPagina) {
    $ids = criarRegistros($modulo, $porPagina * 2, $this->d);
    $excluido = $ids[0];

    // Exclui estando na página 2, do jeito que a tela faz (AJAX)
    $resposta = $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
        ->delete("/admin/$modulo/$excluido", ['page' => 2])
        ->assertOk();

    $tabela = $resposta->json('table');
    expect($tabela)->not->toContain("/admin/$modulo/$excluido?");

    preg_match_all('#href="([^"]*[?&](?:amp;)?page=\d+[^"]*)"#', $tabela, $links);
    expect($links[1])->not->toBeEmpty("A tabela devolvida não tem links de paginação");

    // Clicar em qualquer link da paginação abre a listagem normalmente
    foreach (array_unique($links[1]) as $link) {
        $url = html_entity_decode($link);
        expect(parse_url($url, PHP_URL_PATH))->toBe("/admin/$modulo");
        $this->get($url)->assertOk();
    }
})->with([
    'membros' => ['users', 5],
    'notícias' => ['noticias', 5],
    'parceiros' => ['parceiros', 5],
    'projetos' => ['projetos', 10],
    'serviços' => ['servicos', 10],
    'certificados' => ['certificados', 5],
    'lançamentos' => ['lancamentos', 10],
]);

it('excluir mantém a página em que o usuário estava', function () {
    criarRegistros('users', 10, $this->d);
    $naPagina2 = \App\Models\User::latest()->latest('id')->skip(5)->first();

    $tabela = $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
        ->delete("/admin/users/{$naPagina2->id}", ['page' => 2])
        ->json('table');

    // A página 2 continua sendo exibida (o próximo registro sobe para o lugar do excluído)
    expect($tabela)->toContain('active" aria-current="page"><span class="page-link">2</span>');
});

it('excluir o último item da última página mostra a página anterior', function () {
    // 3 da base + 2 = 5 membros: página 1 cheia, um sexto vai sozinho para a página 2
    criarRegistros('users', 3, $this->d);
    $ultimo = \App\Models\User::oldest()->oldest('id')->first();

    $tabela = $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
        ->delete("/admin/users/{$ultimo->id}", ['page' => 2])
        ->json('table');

    expect($tabela)->toContain('Pessoa 1');
});

it('excluir mantém a busca que estava aplicada', function () {
    criarRegistros('users', 12, $this->d);

    $tabela = $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
        ->delete('/admin/users/' . $this->d['outro']->id, ['search' => 'Pessoa'])
        ->json('table');

    expect($tabela)->not->toContain('Membro Teste')
        ->and(html_entity_decode($tabela))->toContain('search=Pessoa');
});
