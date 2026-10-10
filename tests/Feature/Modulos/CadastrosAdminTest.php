<?php

use App\Models\Certificado;
use App\Models\LancamentoServico;
use App\Models\Noticias;
use App\Models\Parceiro;
use App\Models\Projeto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    usarPublicTemporario();
    $this->d = criarDadosBase();
    $this->actingAs($this->d['admin']);
});

// ── Notícias ─────────────────────────────────────────────────────

it('admin publica notícia com imagem e ela aparece no site', function () {
    $this->post('/admin/noticias', [
        'titulo' => 'Campeonato regional', 'autor' => 'Diretoria', 'conteudo' => 'Resultados do campeonato de 2026.',
        'categoria' => 'Eventos', 'alt' => 'Pódio', 'imagem' => UploadedFile::fake()->image('podio.jpg', 800, 600),
    ])->assertRedirect(route('noticias.index'));

    $noticia = Noticias::where('titulo', 'Campeonato regional')->firstOrFail();
    expect(file_exists(public_path('imagens/noticias/' . $noticia->imagem)))->toBeTrue();

    auth()->logout();
    $this->get('/noticias/cards')->assertOk()->assertSee('Campeonato regional');
    $this->get("/noticias/{$noticia->id}")->assertOk()->assertSee('Resultados do campeonato');
});

it('editar notícia apagando o título mostra erro de validação', function () {
    $this->put('/admin/noticias/' . $this->d['ids']['noticia'], ['titulo' => '', 'autor' => 'Admin', 'conteudo' => 'Conteúdo com mais de 15'])
        ->assertSessionHasErrors('titulo');
});

it('admin exclui notícia', function () {
    $this->delete('/admin/noticias/' . $this->d['ids']['noticia'])->assertRedirect(route('noticias.index'));

    expect(Noticias::find($this->d['ids']['noticia']))->toBeNull();
});

// ── Parceiros ────────────────────────────────────────────────────

it('admin cadastra e exclui parceiro', function () {
    $this->post('/admin/parceiros', [
        'nome' => 'Hobby Shop', 'email' => 'loja@example.com', 'link' => 'https://loja.example.com',
        'alt' => 'Logo da loja', 'imagem' => UploadedFile::fake()->image('logo.png'),
    ])->assertRedirect(route('parceiros.index'));

    $parceiro = Parceiro::where('nome', 'Hobby Shop')->firstOrFail();
    $this->delete("/admin/parceiros/{$parceiro->id}")->assertRedirect(route('parceiros.index'));
    expect(Parceiro::find($parceiro->id))->toBeNull();
});

// ── Projetos, serviços e lançamentos ─────────────────────────────

it('admin cria projeto com imagem e tags', function () {
    $this->post('/admin/projetos', [
        'nome' => 'Drone escolar', 'descricao' => 'Projeto com escolas', 'status' => 'on',
        'imagem' => UploadedFile::fake()->image('drone.jpg'), 'tags' => [$this->d['ids']['tag']],
    ])->assertRedirect(route('projetos.index'));

    $projeto = Projeto::where('nome', 'Drone escolar')->firstOrFail();
    expect($projeto->status)->toBe('concluido')
        ->and($projeto->tags()->pluck('tags.id')->all())->toBe([$this->d['ids']['tag']])
        ->and(file_exists(public_path($projeto->imagem)))->toBeTrue();
});

it('admin cria, edita e exclui serviço', function () {
    $this->post('/admin/servicos', ['descricao' => 'Manutenção de pista'])->assertRedirect(route('servicos.index'));
    $id = DB::table('servicos')->where('descricao', 'Manutenção de pista')->value('id');

    $this->put("/admin/servicos/$id", ['descricao' => 'Manutenção da pista'])->assertRedirect(route('servicos.index'));
    expect(DB::table('servicos')->where('id', $id)->value('descricao'))->toBe('Manutenção da pista');

    $this->delete("/admin/servicos/$id")->assertRedirect(route('servicos.index'));
    expect(DB::table('servicos')->where('id', $id)->exists())->toBeFalse();
});

it('membro registra lançamento de horas para si mesmo', function () {
    $this->actingAs($this->d['membro'])->post('/admin/lancamentos', [
        'projeto_id' => $this->d['ids']['projeto'], 'servico_id' => $this->d['ids']['servico'],
        'data_inicio' => '2026-10-01', 'data_final' => '2026-10-02', 'horas_trabalhadas' => 6,
        'link' => 'https://example.com/fotos', 'user_id' => $this->d['outro']->id,
    ])->assertRedirect(route('lancamentos.index'));

    expect(LancamentoServico::where('horas_trabalhadas', 6)->value('user_id'))->toBe($this->d['membro']->id);
});

it('lançamento com projeto inexistente mostra erro de validação', function () {
    $resposta = $this->actingAs($this->d['membro'])->post('/admin/lancamentos', [
        'projeto_id' => 999999, 'servico_id' => $this->d['ids']['servico'],
        'data_inicio' => '2026-10-01', 'data_final' => '2026-10-02', 'horas_trabalhadas' => 6, 'link' => 'https://example.com',
    ]);

    expect($resposta->status())->toBeLessThan(500, 'Erro 500: ' . $resposta->exception?->getMessage());
    $resposta->assertSessionHasErrors('projeto_id');
});

it('membro vê só os próprios lançamentos e admin vê todos', function () {
    $this->actingAs($this->d['membro'])->get('/admin/lancamentos')->assertOk()->assertDontSee('https://example.com/outro');
    $this->actingAs($this->d['admin'])->get('/admin/lancamentos')->assertOk()->assertSee('Outro Associado');
});

// ── Funções, permissões e cargos ─────────────────────────────────

it('admin cria função com permissões e edita', function () {
    $this->post('/admin/funcoes', ['name' => 'Tesoureiro', 'permissao' => ['Visualizar Membro']])->assertRedirect(route('funcoes.index'));

    $role = Role::findByName('Tesoureiro');
    expect($role->hasPermissionTo('Visualizar Membro'))->toBeTrue();

    $this->put("/admin/funcoes/{$role->id}", ['name' => 'Tesoureiro', 'permissao' => ['Visualizar Notícia']])->assertRedirect();
    $role = $role->fresh();
    expect($role->hasPermissionTo('Visualizar Notícia'))->toBeTrue()->and($role->hasPermissionTo('Visualizar Membro'))->toBeFalse();
});

it('admin cria permissão e não aceita nome repetido', function () {
    $this->post('/admin/permissoes', ['name' => 'Exportar Relatório'])->assertRedirect(route('permissoes.index'));
    expect(Permission::where('name', 'Exportar Relatório')->exists())->toBeTrue();

    $this->post('/admin/permissoes', ['name' => 'Exportar Relatório'])->assertSessionHasErrors('name');
});

it('admin cria, edita e exclui cargo', function () {
    $this->post('/admin/cargos', ['descricao' => 'Tesoureiro'])->assertRedirect(route('cargos.index'));
    $id = DB::table('cargos')->where('descricao', 'Tesoureiro')->value('id');

    $this->put("/admin/cargos/$id", ['descricao' => 'Vice-tesoureiro'])->assertRedirect(route('cargos.index'));
    $this->post('/admin/cargos', ['descricao' => 'Vice-tesoureiro'])->assertSessionHasErrors('descricao');

    $this->delete("/admin/cargos/$id")->assertRedirect(route('cargos.index'));
    expect(DB::table('cargos')->where('id', $id)->exists())->toBeFalse();
});

// ── Frases do site ───────────────────────────────────────────────

function atualizarTextosDaHome($teste): void
{
    $teste->put('/admin/frase-inicio/atualizar', [
        'titulo' => 'Título novo da home', 'subtitulo' => 'Subtítulo novo', 'localizacao' => 'Londrina - PR',
        'frase_inicio' => 'Frase nova da home', 'descricao' => 'Descrição nova', 'como_associar_se' => 'Preencha o formulário',
    ])->assertRedirect(route('admin.frase_inicio.editar'));

    auth()->logout();
}

it('admin altera subtítulo, localização e descrição da home', function () {
    atualizarTextosDaHome($this);

    $this->get('/')->assertOk()->assertSee('Subtítulo novo')->assertSee('Londrina - PR')->assertSee('Descrição nova');
});

it('a frase de início editada pelo admin aparece na home', function () {
    atualizarTextosDaHome($this);

    $this->get('/')->assertOk()->assertSee('Frase nova da home');
});

// ── Galeria ──────────────────────────────────────────────────────

it('admin cadastra evento de fotos na galeria', function () {
    $this->post('/admin/galeria', [
        'titulo' => 'Festival 2026', 'data_inicio_evento' => '2026-08-10', 'tipo_midia' => 'imagem',
        'imagem_principal' => UploadedFile::fake()->image('capa.jpg'),
        'arquivos' => [UploadedFile::fake()->image('a.jpg'), UploadedFile::fake()->image('b.jpg')],
    ])->assertRedirect(route('galeria.indexAdmin'));

    $galeria = \App\Models\Galeria::where('titulo', 'Festival 2026')->firstOrFail();
    expect($galeria->ano)->toBe(2026)->and(DB::table('galeria_midias')->where('galeria_id', $galeria->id)->count())->toBe(2);

    auth()->logout();
    $this->get('/galeria/ano/2026')->assertOk()->assertSee('Festival 2026');
});

it('admin cadastra evento de vídeo do YouTube', function () {
    $this->post('/admin/galeria', [
        'titulo' => 'Vídeo do voo', 'data_inicio_evento' => '2026-08-10', 'tipo_midia' => 'video',
        'link_youtube' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
    ])->assertRedirect(route('galeria.indexAdmin'));

    expect(\App\Models\Galeria::where('titulo', 'Vídeo do voo')->value('caminho'))->toBe('https://www.youtube.com/watch?v=dQw4w9WgXcQ');
});

it('admin exclui evento da galeria', function () {
    $this->delete('/admin/galeria/' . $this->d['ids']['galeria'])->assertRedirect();

    expect(DB::table('galerias')->where('id', $this->d['ids']['galeria'])->exists())->toBeFalse();
});

// ── Certificados ─────────────────────────────────────────────────

it('admin edita certificado', function () {
    $resposta = $this->put('/admin/certificados/' . $this->d['ids']['certificado'], [
        'user_id' => $this->d['membro']->id, 'descricao' => 'Participação no festival', 'horas' => 8, 'data' => '2026-08-10',
    ]);

    expect($resposta->status())->toBeLessThan(500, 'Erro 500: ' . $resposta->exception?->getMessage());
    expect(Certificado::find($this->d['ids']['certificado'])->horas)->toBe(8);
});

it('admin cria certificado manual', function () {
    $this->post('/admin/certificados', ['manual_certificado' => [
        'user_id' => $this->d['membro']->id, 'horas' => 3, 'data' => '2026-09-01', 'descricao' => 'Oficina de solda',
    ]])->assertRedirect(route('certificados.index'));

    expect(Certificado::where('descricao', 'Oficina de solda')->exists())->toBeTrue();
});

it('visitante valida certificado pelo token sem ver dados pessoais do associado', function () {
    auth()->logout();
    $token = Certificado::find($this->d['ids']['certificado'])->token;
    $this->d['membro']->update(['rg' => '12.345.678-9', 'telefone_celular' => '43999990000']);

    $resposta = $this->postJson('/certificados/validar', ['token' => $token])->assertOk();

    $texto = json_encode($resposta->json());
    expect($texto)->not->toContain($this->d['membro']->cpf)
        ->not->toContain($this->d['membro']->email)
        ->not->toContain('43999990000');
});

it('visitante encontra certificados digitando o CPF com pontuação', function () {
    auth()->logout();
    $cpf = $this->d['membro']->cpf;
    $formatado = substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);

    $this->postJson('/certificados/buscar', ['cpf' => $formatado])->assertOk()->assertJsonCount(1, 'certificados');
});
