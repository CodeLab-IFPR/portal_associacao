<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/**
 * Vários controllers gravam uploads direto em public_path() e o ImageUploader
 * cria pastas relativas ao diretório corrente. Para os testes não sujarem o
 * projeto, aponta public_path() e o diretório corrente para uma pasta temporária,
 * removida ao fim do teste.
 */
function usarPublicTemporario(): string
{
    $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'amaer-public-' . uniqid();
    foreach (['imagens/noticias', 'imagens/parceiros', 'imagens/users', 'imagens/projetos', 'img/fotos', 'anexos_submissions'] as $sub) {
        \Illuminate\Support\Facades\File::ensureDirectoryExists("$dir/$sub");
    }
    // Modelo usado para gerar o PDF dos certificados
    \Illuminate\Support\Facades\File::copyDirectory(public_path('certificate'), "$dir/certificate");

    $cwd = getcwd();
    chdir($dir);
    app()->usePublicPath($dir);
    test()->withoutVite();

    test()->beforeApplicationDestroyed(function () use ($dir, $cwd) {
        chdir($cwd);
        \Illuminate\Support\Facades\File::deleteDirectory($dir);
    });

    return $dir;
}

/** Pequeno PDF válido para uploads. */
function pdfFalso(string $nome = 'arquivo.pdf'): \Illuminate\Http\UploadedFile
{
    return \Illuminate\Http\UploadedFile::fake()->createWithContent($nome, "%PDF-1.4\n1 0 obj<<>>endobj\ntrailer<<>>\n%%EOF");
}

/**
 * Cria um conjunto mínimo de dados com um registro de cada módulo, para que
 * as telas com parâmetro ({user}, {invoice}, ...) tenham o que exibir.
 *
 * Retorna os usuários (admin, membro, outro) e os ids criados por tabela.
 */
function criarDadosBase(): array
{
    test()->seed(\Database\Seeders\PermissionsSeeder::class);
    test()->seed(\Database\Seeders\FraseInicioSeeder::class);

    $admin = \App\Models\User::factory()->create(['name' => 'Admin Teste']);
    $admin->assignRole('Admin');

    $membro = \App\Models\User::factory()->create(['name' => 'Membro Teste']);
    $membro->assignRole('Membro');

    // Outro associado, para testar se um membro enxerga dados de terceiros
    $outro = \App\Models\User::factory()->create(['name' => 'Outro Associado']);
    $outro->assignRole('Membro');

    $agora = now();
    $inserir = fn (string $tabela, array $dados) => \Illuminate\Support\Facades\DB::table($tabela)
        ->insertGetId($dados + ['created_at' => $agora, 'updated_at' => $agora]);

    $ids = [];
    $ids['cargo'] = $inserir('cargos', ['descricao' => 'Diretor']);
    $ids['tag'] = $inserir('tags', ['name' => 'Eventos']);
    $ids['servico'] = $inserir('servicos', ['descricao' => 'Organização de evento']);
    $ids['projeto'] = $inserir('projetos', ['nome' => 'Projeto Teste', 'descricao' => 'Descrição', 'status' => 'ativo']);
    $inserir('projeto_tag', ['projeto_id' => $ids['projeto'], 'tag_id' => $ids['tag']]);
    $ids['ata'] = $inserir('atas', ['titulo' => 'ATA Teste', 'descricao' => 'Reunião ordinária']);
    $ids['certificado'] = $inserir('certificados', [
        // Mesmo tamanho de token que o CertificadoController gera (Str::random(10))
        'user_id' => $membro->id, 'token' => \Illuminate\Support\Str::random(10),
        'descricao' => 'Participação', 'horas' => 4, 'data' => $agora->toDateString(),
    ]);
    $ids['mensagem'] = $inserir('contacts', ['name' => 'Visitante', 'email' => 'visitante@example.com', 'message' => 'Olá', 'subject' => 'Dúvida']);
    $ids['submission'] = $inserir('submissions', ['name' => 'Visitante', 'email' => 'visitante@example.com', 'demand_description' => 'Demanda', 'expected_utility' => 'Utilidade']);
    $ids['documento'] = $inserir('documentos', [
        'user_id' => $membro->id, 'nome_arquivo' => 'doc.pdf', 'nome_original' => 'rg.pdf',
        'caminho_arquivo' => 'documentos/doc.pdf', 'tipo_documento' => 'RG', 'status' => 'pendente',
    ]);
    $ids['documento_outro'] = $inserir('documentos', [
        'user_id' => $outro->id, 'nome_arquivo' => 'doc2.pdf', 'nome_original' => 'cpf.pdf',
        'caminho_arquivo' => 'documentos/doc2.pdf', 'tipo_documento' => 'CPF', 'status' => 'pendente',
    ]);
    $ids['galeria'] = $inserir('galerias', ['titulo' => 'Evento 2026', 'caminho' => 'galeria/capa.jpg', 'tipo' => 'imagem', 'ano' => 2026]);
    $inserir('galeria_midias', ['galeria_id' => $ids['galeria'], 'tipo' => 'imagem', 'caminho' => 'galeria/foto1.jpg']);
    $ids['lancamento'] = $inserir('lancamento_servicos', [
        'projeto_id' => $ids['projeto'], 'servico_id' => $ids['servico'], 'user_id' => $membro->id,
        'data_inicio' => $agora->toDateString(), 'data_final' => $agora->toDateString(),
        'horas_trabalhadas' => 4, 'link' => 'https://example.com',
    ]);
    $ids['lancamento_outro'] = $inserir('lancamento_servicos', [
        'projeto_id' => $ids['projeto'], 'servico_id' => $ids['servico'], 'user_id' => $outro->id,
        'data_inicio' => $agora->toDateString(), 'data_final' => $agora->toDateString(),
        'horas_trabalhadas' => 8, 'link' => 'https://example.com/outro',
    ]);
    $ids['midia'] = \Illuminate\Support\Facades\DB::table('galeria_midias')->where('galeria_id', $ids['galeria'])->value('id');
    $ids['noticia'] = $inserir('noticias', [
        'titulo' => 'Notícia Teste', 'autor' => 'Admin', 'conteudo' => '<p>Conteúdo</p>',
        'categoria' => 'Geral', 'alt' => 'Imagem', 'imagem' => 'noticia.jpg',
    ]);
    $ids['parceiro'] = $inserir('parceiros', ['nome' => 'Parceiro', 'email' => 'parceiro@example.com', 'link' => 'https://example.com', 'imagem' => 'parceiro.jpg', 'alt' => 'Logo']);

    foreach (['invoice' => $membro, 'invoice_outro' => $outro] as $chave => $dono) {
        $ids[$chave] = $inserir('invoices', [
            'user_id' => $dono->id, 'total_amount' => 200, 'installments_count' => 2,
            'periodicity' => 'mensal', 'first_due_date' => $agora->copy()->addDays(5)->toDateString(), 'status' => 'pendente',
        ]);
        foreach ([1, 2] as $n) {
            $ids["{$chave}_parcela_$n"] = $inserir('invoice_installments', [
                'invoice_id' => $ids[$chave], 'installment_number' => $n, 'amount' => 100,
                'due_date' => $agora->copy()->addDays(5)->addMonths($n - 1)->toDateString(), 'status' => 'pendente',
            ]);
        }
    }

    $ids['role'] = \Spatie\Permission\Models\Role::where('name', 'Membro')->value('id');
    $ids['permissao'] = \Spatie\Permission\Models\Permission::value('id');

    return compact('admin', 'membro', 'outro', 'ids');
}
