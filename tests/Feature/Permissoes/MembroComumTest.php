<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/*
| O que um associado comum (papel "Membro") NÃO pode fazer.
| Cada caso tenta a ação de verdade e confere que foi bloqueada
| (status 403/404) e que o banco não mudou.
*/

beforeEach(function () {
    Storage::fake('public');
    Storage::fake('local');
    Storage::fake('private');
    $this->d = criarDadosBase();
    $this->actingAs($this->d['membro']);
});

/** Telas que o membro não deve conseguir abrir. */
dataset('telas restritas', [
    'mensagens de contato (lista)' => fn () => '/admin/mensagens',
    'mensagem de contato' => fn () => '/admin/mensagens/' . test()->d['ids']['mensagem'],
    'editar frase da página inicial' => fn () => '/admin/frase-inicio/editar',
    'tags (lista)' => fn () => '/admin/tags',
    'criar tag' => fn () => '/admin/tags/create',
    'editar tag' => fn () => '/admin/tags/' . test()->d['ids']['tag'] . '/edit',
    'criar cargo' => fn () => '/admin/cargos/create',
    'editar cargo' => fn () => '/admin/cargos/' . test()->d['ids']['cargo'] . '/edit',
    'fatura de outro associado' => fn () => '/admin/faturas/' . test()->d['ids']['invoice_outro'],
    'documento de outro associado' => fn () => '/admin/documentos/' . test()->d['ids']['documento_outro'],
    'editar documento de outro associado' => fn () => '/admin/documentos/' . test()->d['ids']['documento_outro'] . '/edit',
    'baixar documento de outro associado' => fn () => '/admin/documentos/' . test()->d['ids']['documento_outro'] . '/download',
    'documentos de outro associado' => fn () => '/admin/documentos/usuario/' . test()->d['outro']->id,
    'editar lançamento de outro associado' => fn () => '/admin/lancamentos/' . test()->d['ids']['lancamento_outro'] . '/edit',
    'criar fatura' => fn () => '/admin/faturas/create',
    'lista de membros' => fn () => '/admin/users',
]);

it('não abre tela restrita', function (string $url) {
    $resposta = $this->get($url);

    expect($resposta->status())->toBeIn([403, 404], "Membro abriu {$url} (status {$resposta->status()})");
})->with('telas restritas');

/**
 * Ações que o membro não deve conseguir executar.
 * Cada caso: [método, url, dados, função que lê o estado a ser preservado].
 */
dataset('ações restritas', [
    'criar tag' => fn () => ['post', '/admin/tags', ['tags' => ['Invasão']],
        fn () => DB::table('tags')->count()],
    'renomear tag' => fn () => ['put', '/admin/tags/' . test()->d['ids']['tag'], ['name' => 'Invasão'],
        fn () => DB::table('tags')->where('id', test()->d['ids']['tag'])->value('name')],
    'excluir tag' => fn () => ['delete', '/admin/tags/' . test()->d['ids']['tag'], [],
        fn () => DB::table('tags')->count()],
    'criar cargo' => fn () => ['post', '/admin/cargos', ['descricao' => 'Invasão'],
        fn () => DB::table('cargos')->count()],
    'renomear cargo' => fn () => ['put', '/admin/cargos/' . test()->d['ids']['cargo'], ['descricao' => 'Invasão'],
        fn () => DB::table('cargos')->where('id', test()->d['ids']['cargo'])->value('descricao')],
    'excluir cargo' => fn () => ['delete', '/admin/cargos/' . test()->d['ids']['cargo'], [],
        fn () => DB::table('cargos')->count()],
    'alterar frase da página inicial' => fn () => ['put', '/admin/frase-inicio/atualizar', [
        'titulo' => 'Invasão', 'subtitulo' => 'x', 'localizacao' => 'x', 'frase_inicio' => 'Invasão', 'descricao' => 'x',
    ], fn () => DB::table('frase_inicio')->pluck('frase')->all()],
    'excluir mensagem de contato' => fn () => ['delete', '/admin/mensagens/' . test()->d['ids']['mensagem'], [],
        fn () => DB::table('contacts')->count()],
    'excluir mensagens selecionadas' => fn () => ['delete', '/admin/mensagens/deleteSelected', ['ids' => [test()->d['ids']['mensagem']]],
        fn () => DB::table('contacts')->count()],
    'marcar mensagem como lida' => fn () => ['post', '/admin/mensagens/' . test()->d['ids']['mensagem'] . '/mark-read', [],
        fn () => DB::table('contacts')->where('id', test()->d['ids']['mensagem'])->value('read')],
    'excluir submissões selecionadas' => fn () => ['delete', '/admin/submissions/deleteSelected', ['ids' => [test()->d['ids']['submission']]],
        fn () => DB::table('submissions')->count()],
    'excluir mídia da galeria' => fn () => ['delete', '/admin/galeria/media/' . test()->d['ids']['midia'], [],
        fn () => DB::table('galeria_midias')->count()],
    'aprovar o próprio documento' => fn () => ['patch', '/admin/documentos/' . test()->d['ids']['documento'] . '/aprovar', [],
        fn () => DB::table('documentos')->where('id', test()->d['ids']['documento'])->value('status')],
    'editar documento de outro associado' => fn () => ['put', '/admin/documentos/' . test()->d['ids']['documento_outro'], ['tipo_documento' => 'Invasão'],
        fn () => DB::table('documentos')->where('id', test()->d['ids']['documento_outro'])->value('tipo_documento')],
    'excluir documento de outro associado' => fn () => ['delete', '/admin/documentos/' . test()->d['ids']['documento_outro'], [],
        fn () => DB::table('documentos')->count()],
    'editar lançamento de outro associado' => fn () => ['put', '/admin/lancamentos/' . test()->d['ids']['lancamento_outro'], [
        'projeto_id' => test()->d['ids']['projeto'], 'servico_id' => test()->d['ids']['servico'],
        'data_inicio' => '2026-01-01', 'data_final' => '2026-01-02', 'horas_trabalhadas' => 999, 'link' => 'https://example.com/x',
    ], fn () => DB::table('lancamento_servicos')->where('id', test()->d['ids']['lancamento_outro'])->value('horas_trabalhadas')],
    'passar o próprio lançamento para outro usuário' => fn () => ['put', '/admin/lancamentos/' . test()->d['ids']['lancamento'], [
        'projeto_id' => test()->d['ids']['projeto'], 'servico_id' => test()->d['ids']['servico'],
        'data_inicio' => '2026-01-01', 'data_final' => '2026-01-02', 'horas_trabalhadas' => 4, 'link' => 'https://example.com',
        'user_id' => test()->d['outro']->id, 'certificado_gerado' => 1,
    ], fn () => DB::table('lancamento_servicos')->where('id', test()->d['ids']['lancamento'])->first(['user_id', 'certificado_gerado'])],
    'dar baixa na própria parcela' => fn () => ['patch', '/admin/pendencias/installments/' . test()->d['ids']['invoice_parcela_1'] . '/baixar', [],
        fn () => DB::table('invoice_installments')->where('id', test()->d['ids']['invoice_parcela_1'])->value('status')],
    'dar baixa em parcela de outro associado' => fn () => ['patch', '/admin/pendencias/installments/' . test()->d['ids']['invoice_outro_parcela_1'] . '/baixar', [],
        fn () => DB::table('invoice_installments')->where('id', test()->d['ids']['invoice_outro_parcela_1'])->value('status')],
    'editar parcela da própria fatura' => fn () => ['patch', '/admin/faturas/' . test()->d['ids']['invoice'] . '/installments/' . test()->d['ids']['invoice_parcela_1'], [
        'amount' => 1, 'installments_count' => 2, 'due_date' => '2026-01-01', 'status' => 'paga',
    ], fn () => DB::table('invoice_installments')->where('id', test()->d['ids']['invoice_parcela_1'])->value('status')],
    'gerar certificados' => fn () => ['post', '/admin/certificados/generate', [],
        fn () => DB::table('certificados')->count()],
]);

it('não executa ação restrita', function (array $caso) {
    [$metodo, $url, $dados, $estado] = $caso;
    $antes = $estado();

    $resposta = $this->{$metodo}($url, $dados);

    expect($estado())->toEqual($antes, "Membro alterou dados via {$metodo} {$url} (status {$resposta->status()})");
    expect($resposta->status())->toBeLessThan(500, "Erro {$resposta->status()} em {$metodo} {$url}: " . $resposta->exception?->getMessage());
})->with('ações restritas');
