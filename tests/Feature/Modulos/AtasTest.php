<?php

use App\Models\Ata;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('local');
    $this->d = criarDadosBase();
});

it('admin cadastra ATA com PDF', function () {
    $this->actingAs($this->d['admin'])
        ->post('/admin/admin/atas', ['titulo' => 'Assembleia', 'descricao' => 'Pauta', 'arquivo' => pdfFalso('ata-marco.pdf')])
        ->assertRedirect(route('admin.atas.index'))
        ->assertSessionHas('success');

    $ata = Ata::where('titulo', 'Assembleia')->firstOrFail();
    expect($ata->arquivo_original)->toBe('ata-marco.pdf');
    Storage::assertExists('public/atas/' . $ata->arquivo);
});

it('valida os campos da ATA', function (array $dados, string $campo) {
    $this->actingAs($this->d['admin'])
        ->post('/admin/admin/atas', $dados)
        ->assertSessionHasErrors($campo);
})->with([
    'sem título' => [fn () => ['descricao' => 'x', 'arquivo' => pdfFalso()], 'titulo'],
    'sem descrição' => [fn () => ['titulo' => 'x', 'arquivo' => pdfFalso()], 'descricao'],
    'sem arquivo' => [fn () => ['titulo' => 'x', 'descricao' => 'x'], 'arquivo'],
    'arquivo que não é PDF' => [fn () => ['titulo' => 'x', 'descricao' => 'x', 'arquivo' => UploadedFile::fake()->image('foto.jpg')], 'arquivo'],
]);

it('admin edita ATA trocando o arquivo e o antigo é apagado', function () {
    $this->actingAs($this->d['admin'])
        ->post('/admin/admin/atas', ['titulo' => 'Antiga', 'descricao' => 'x', 'arquivo' => pdfFalso()]);
    $ata = Ata::where('titulo', 'Antiga')->firstOrFail();
    $arquivoAntigo = $ata->arquivo;

    $this->travel(2)->seconds();
    $this->put("/admin/admin/atas/{$ata->id}", ['titulo' => 'Nova', 'descricao' => 'y', 'arquivo' => pdfFalso('novo.pdf')])
        ->assertRedirect(route('admin.atas.index'));

    $ata->refresh();
    expect($ata->titulo)->toBe('Nova')->and($ata->arquivo_original)->toBe('novo.pdf');
    Storage::assertMissing('public/atas/' . $arquivoAntigo);
    Storage::assertExists('public/atas/' . $ata->arquivo);
});

it('admin exclui ATA e o arquivo', function () {
    $this->actingAs($this->d['admin'])
        ->post('/admin/admin/atas', ['titulo' => 'Excluir', 'descricao' => 'x', 'arquivo' => pdfFalso()]);
    $ata = Ata::where('titulo', 'Excluir')->firstOrFail();

    $this->delete("/admin/admin/atas/{$ata->id}")->assertRedirect(route('admin.atas.index'));

    expect(Ata::find($ata->id))->toBeNull();
    Storage::assertMissing('public/atas/' . $ata->arquivo);
});

it('admin baixa o PDF da ATA', function () {
    $this->actingAs($this->d['admin'])
        ->post('/admin/admin/atas', ['titulo' => 'Baixar', 'descricao' => 'x', 'arquivo' => pdfFalso('original.pdf')]);
    $ata = Ata::where('titulo', 'Baixar')->firstOrFail();

    $this->get("/admin/admin/atas/{$ata->id}/download")
        ->assertOk()
        ->assertDownload('original.pdf');
});

it('download de ATA sem arquivo retorna 404', function () {
    $this->actingAs($this->d['admin'])
        ->get('/admin/admin/atas/' . $this->d['ids']['ata'] . '/download')
        ->assertNotFound();
});

it('membro vê a lista e o detalhe das ATAs', function () {
    $this->actingAs($this->d['membro']);

    $this->get('/admin/admin/atas')->assertOk()->assertSee('ATA Teste');
    $this->get('/admin/admin/atas/' . $this->d['ids']['ata'])->assertOk()->assertSee('ATA Teste');
});

it('o botão de download que o membro vê funciona', function () {
    $this->actingAs($this->d['admin'])
        ->post('/admin/admin/atas', ['titulo' => 'Pública', 'descricao' => 'x', 'arquivo' => pdfFalso()]);
    $ata = Ata::where('titulo', 'Pública')->firstOrFail();

    $this->actingAs($this->d['membro']);
    $download = route('admin.atas.download', $ata);

    // A lista mostra o botão de download para qualquer usuário...
    $this->get('/admin/admin/atas')->assertSee($download, false);
    // ...então o link precisa funcionar para ele
    $this->get($download)->assertOk();
});
