<?php

use App\Models\Documento;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    // O download monta o caminho com storage_path('app/private/...') em vez de usar o disco,
    // então o disco e o storage_path precisam apontar para a mesma pasta temporária.
    $storage = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'amaer-storage-' . uniqid();
    $original = $this->app->storagePath();
    $this->app->useStoragePath($storage);
    config(['filesystems.disks.private.root' => "$storage/app/private"]);
    $this->beforeApplicationDestroyed(function () use ($storage, $original) {
        $this->app->useStoragePath($original);
        \Illuminate\Support\Facades\File::deleteDirectory($storage);
    });

    $this->d = criarDadosBase();
});

function enviarDocumento($teste, string $tipo = 'RG'): Documento
{
    $teste->post('/admin/documentos', ['arquivo' => pdfFalso('meu-rg.pdf'), 'tipo_documento' => $tipo, 'descricao' => 'Frente e verso'])
        ->assertRedirect(route('documentos.index'));

    return Documento::where('tipo_documento', $tipo)->latest('id')->firstOrFail();
}

it('membro envia documento PDF e ele fica pendente', function () {
    $this->actingAs($this->d['membro']);
    $doc = enviarDocumento($this, 'Comprovante');

    expect($doc->user_id)->toBe($this->d['membro']->id)
        ->and($doc->status)->toBe('pendente')
        ->and($doc->nome_original)->toBe('meu-rg.pdf');
    Storage::disk('private')->assertExists($doc->caminho_arquivo);
});

it('rejeita documento que não é PDF', function () {
    $this->actingAs($this->d['membro'])
        ->post('/admin/documentos', ['arquivo' => UploadedFile::fake()->image('foto.png'), 'tipo_documento' => 'RG'])
        ->assertSessionHasErrors('arquivo');
});

it('membro vê só os próprios documentos e admin vê todos', function () {
    $this->actingAs($this->d['membro'])->get('/admin/documentos')->assertOk()
        ->assertViewHas('documentos', fn ($docs) => $docs->pluck('user_id')->unique()->all() === [$this->d['membro']->id]);

    $this->actingAs($this->d['admin'])->get('/admin/documentos')->assertOk()
        ->assertViewHas('documentos', fn ($docs) => $docs->count() === 2);
});

it('membro baixa o próprio documento', function () {
    $this->actingAs($this->d['membro']);
    $doc = enviarDocumento($this);

    $this->get("/admin/documentos/{$doc->id}/download")->assertOk()->assertDownload('meu-rg.pdf');
});

it('admin aprova e rejeita documentos', function () {
    $this->actingAs($this->d['membro']);
    $aprovar = enviarDocumento($this, 'Aprovar');
    $rejeitar = enviarDocumento($this, 'Rejeitar');

    $this->actingAs($this->d['admin']);
    $this->patch("/admin/documentos/{$aprovar->id}/aprovar")->assertRedirect();
    $this->patch("/admin/documentos/{$rejeitar->id}/rejeitar", ['observacoes' => 'Ilegível'])->assertRedirect();

    expect($aprovar->fresh()->status)->toBe('aprovado')
        ->and($aprovar->fresh()->aprovado_por)->toBe($this->d['admin']->id)
        ->and($rejeitar->fresh()->status)->toBe('rejeitado');
});

it('admin consegue ver, baixar e excluir o documento de um membro', function () {
    $this->actingAs($this->d['membro']);
    $doc = enviarDocumento($this);

    $this->actingAs($this->d['admin']);
    $this->get("/admin/documentos/{$doc->id}")->assertOk();
    $this->get("/admin/documentos/{$doc->id}/download")->assertOk();
    $this->delete("/admin/documentos/{$doc->id}")->assertRedirect();

    expect(Documento::find($doc->id))->toBeNull();
});

it('membro exclui o próprio documento e o arquivo é apagado', function () {
    $this->actingAs($this->d['membro']);
    $doc = enviarDocumento($this);

    $this->delete("/admin/documentos/{$doc->id}")->assertRedirect(route('documentos.index'));

    expect(Documento::find($doc->id))->toBeNull();
    Storage::disk('private')->assertMissing($doc->caminho_arquivo);
});

it('depois de enviar, o associado vê a confirmação na lista de documentos', function () {
    $this->actingAs($this->d['membro'])->followingRedirects()
        ->post('/admin/documentos', ['arquivo' => pdfFalso(), 'tipo_documento' => 'RG'])
        ->assertSee('Documento enviado com sucesso! Aguarde a aprovação.');
});
