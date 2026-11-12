<?php

use App\Models\Invoice;
use App\Models\InvoiceInstallment;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    $this->d = criarDadosBase();
});

function dadosFatura(array $userIds, array $extra = []): array
{
    return $extra + [
        'user_ids' => $userIds,
        'installment_amount' => 50,
        'installments_count' => 3,
        'first_due_date' => '2026-11-10',
        'periodicity' => 'mensal',
        'notes' => 'Anuidade',
    ];
}

it('admin cria uma fatura por associado com as parcelas', function () {
    $ids = [$this->d['membro']->id, $this->d['outro']->id];

    $this->actingAs($this->d['admin'])
        ->post('/admin/faturas', dadosFatura($ids))
        ->assertRedirect(route('invoices.index'))
        ->assertSessionHas('success');

    foreach ($ids as $userId) {
        $fatura = Invoice::where('user_id', $userId)->where('notes', 'Anuidade')->firstOrFail();
        expect((float) $fatura->total_amount)->toBe(150.0)
            ->and($fatura->status)->toBe('pendente')
            ->and($fatura->installments()->orderBy('installment_number')->pluck('due_date')->map->toDateString()->all())
            ->toBe(['2026-11-10', '2026-12-10', '2027-01-10']);
    }
});

it('calcula o vencimento das parcelas pela periodicidade', function (string $periodicidade, array $vencimentos) {
    $this->actingAs($this->d['admin'])
        ->post('/admin/faturas', dadosFatura([$this->d['membro']->id], ['periodicity' => $periodicidade, 'notes' => $periodicidade]));

    $fatura = Invoice::where('notes', $periodicidade)->firstOrFail();
    expect($fatura->installments()->orderBy('installment_number')->pluck('due_date')->map->toDateString()->all())->toBe($vencimentos);
})->with([
    'semanal' => ['semanal', ['2026-11-10', '2026-11-17', '2026-11-24']],
    'quinzenal' => ['quinzenal', ['2026-11-10', '2026-11-24', '2026-12-08']],
    'trimestral' => ['trimestral', ['2026-11-10', '2027-02-10', '2027-05-10']],
    'anual' => ['anual', ['2026-11-10', '2027-11-10', '2028-11-10']],
]);

it('valida os campos da fatura', function (array $sobrescrever, string $campo) {
    $this->actingAs($this->d['admin'])
        ->post('/admin/faturas', array_merge(dadosFatura([test()->d['membro']->id]), $sobrescrever))
        ->assertSessionHasErrors($campo);

    expect(Invoice::where('notes', 'Anuidade')->exists())->toBeFalse();
})->with([
    'sem associado' => [['user_ids' => []], 'user_ids'],
    'associado inexistente' => [['user_ids' => [999999]], 'user_ids.0'],
    'valor zero' => [['installment_amount' => 0], 'installment_amount'],
    'mais de 60 parcelas' => [['installments_count' => 61], 'installments_count'],
    'periodicidade inválida' => [['periodicity' => 'diaria'], 'periodicity'],
    'sem vencimento' => [['first_due_date' => ''], 'first_due_date'],
]);

it('a tela de nova fatura lista apenas associados com papel Membro', function () {
    $this->actingAs($this->d['admin'])
        ->get('/admin/faturas/create')
        ->assertOk()
        ->assertViewHas('members', fn ($membros) => $membros->pluck('name')->sort()->values()->all() === ['Membro Teste', 'Outro Associado']);
});

it('admin edita a observação da fatura', function () {
    $this->actingAs($this->d['admin'])
        ->put('/admin/faturas/' . $this->d['ids']['invoice'], ['notes' => 'Nova observação'])
        ->assertRedirect(route('invoices.show', $this->d['ids']['invoice']));

    expect(Invoice::find($this->d['ids']['invoice'])->notes)->toBe('Nova observação');
});

it('admin exclui a fatura e as parcelas vão junto', function () {
    $this->actingAs($this->d['admin'])
        ->delete('/admin/faturas/' . $this->d['ids']['invoice'])
        ->assertRedirect(route('invoices.index'));

    expect(Invoice::find($this->d['ids']['invoice']))->toBeNull()
        ->and(InvoiceInstallment::where('invoice_id', $this->d['ids']['invoice'])->count())->toBe(0);
});

it('admin anexa e troca o boleto da parcela', function () {
    $fatura = $this->d['ids']['invoice'];
    $parcela = $this->d['ids']['invoice_parcela_1'];
    $this->actingAs($this->d['admin']);

    $this->patch("/admin/faturas/$fatura/installments/$parcela/boleto", ['boleto' => pdfFalso('boleto1.pdf')])
        ->assertRedirect(route('invoices.show', $fatura));
    $primeiro = InvoiceInstallment::find($parcela)->boleto_path;
    Storage::disk('public')->assertExists($primeiro);

    $this->patch("/admin/faturas/$fatura/installments/$parcela/boleto", ['boleto' => pdfFalso('boleto2.pdf')]);
    $segundo = InvoiceInstallment::find($parcela)->boleto_path;

    expect($segundo)->not->toBe($primeiro);
    Storage::disk('public')->assertMissing($primeiro);
    Storage::disk('public')->assertExists($segundo);
});

it('não anexa boleto em parcela de outra fatura', function () {
    $this->actingAs($this->d['admin'])
        ->patch('/admin/faturas/' . $this->d['ids']['invoice'] . '/installments/' . $this->d['ids']['invoice_outro_parcela_1'] . '/boleto', ['boleto' => pdfFalso()])
        ->assertNotFound();
});

it('aceita boleto de até 5MB e recusa acima disso', function (int $kb, bool $aceito) {
    $fatura = $this->d['ids']['invoice'];
    $parcela = $this->d['ids']['invoice_parcela_1'];
    $arquivo = \Illuminate\Http\UploadedFile::fake()->create('boleto.pdf', $kb, 'application/pdf');

    $resposta = $this->actingAs($this->d['admin'])
        ->patch("/admin/faturas/$fatura/installments/$parcela/boleto", ['boleto' => $arquivo]);

    if ($aceito) {
        $resposta->assertSessionHasNoErrors();
        expect(InvoiceInstallment::find($parcela)->boleto_path)->not->toBeNull();
    } else {
        $resposta->assertSessionHasErrors(['boleto' => 'O boleto deve ter no máximo 5MB.']);
        expect(InvoiceInstallment::find($parcela)->boleto_path)->toBeNull();
    }
})->with([
    'exatamente 5MB' => [5120, true],
    'acima de 5MB' => [5121, false],
]);

it('admin e o dono da fatura abrem o boleto anexado', function () {
    $fatura = $this->d['ids']['invoice'];
    $parcela = $this->d['ids']['invoice_parcela_1'];
    $url = "/admin/faturas/$fatura/installments/$parcela/boleto";

    $this->actingAs($this->d['admin'])->patch($url, ['boleto' => pdfFalso()]);

    foreach (['admin', 'membro'] as $quem) {
        $this->actingAs($this->d[$quem])->get($url)
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf')
            ->assertHeader('Content-Disposition', 'inline; filename=boleto-fatura-' . $fatura . '-parcela-1.pdf');
    }

    $this->actingAs($this->d['membro'])->get("$url?download=1")
        ->assertOk()
        ->assertDownload('boleto-fatura-' . $fatura . '-parcela-1.pdf');
});

it('associado não abre boleto de fatura de outra pessoa', function () {
    $fatura = $this->d['ids']['invoice_outro'];
    $parcela = $this->d['ids']['invoice_outro_parcela_1'];

    $this->actingAs($this->d['admin'])
        ->patch("/admin/faturas/$fatura/installments/$parcela/boleto", ['boleto' => pdfFalso()]);

    $this->actingAs($this->d['membro'])
        ->get("/admin/faturas/$fatura/installments/$parcela/boleto")
        ->assertForbidden();
});

it('boleto não anexado ou de parcela de outra fatura retorna 404', function () {
    $this->actingAs($this->d['admin']);

    $this->get('/admin/faturas/' . $this->d['ids']['invoice'] . '/installments/' . $this->d['ids']['invoice_parcela_1'] . '/boleto')
        ->assertNotFound();

    $this->get('/admin/faturas/' . $this->d['ids']['invoice'] . '/installments/' . $this->d['ids']['invoice_outro_parcela_1'] . '/boleto')
        ->assertNotFound();
});

it('a tela da fatura aponta o botão de visualizar para a rota do boleto', function () {
    $fatura = $this->d['ids']['invoice'];
    $parcela = $this->d['ids']['invoice_parcela_1'];

    $this->actingAs($this->d['admin'])
        ->patch("/admin/faturas/$fatura/installments/$parcela/boleto", ['boleto' => pdfFalso()]);

    $this->actingAs($this->d['admin'])->get("/admin/faturas/$fatura")
        ->assertOk()
        ->assertSee('data-url="' . route('invoices.installments.boleto.show', [$fatura, $parcela]) . '"', false)
        ->assertDontSee('/storage/boletos/', false);
});

it('admin marca parcela como paga pela edição', function () {
    $this->actingAs($this->d['admin'])
        ->patch('/admin/faturas/' . $this->d['ids']['invoice'] . '/installments/' . $this->d['ids']['invoice_parcela_1'], [
            'amount' => 100, 'installments_count' => 2, 'due_date' => '2026-11-10', 'payment_date' => '2026-11-09', 'status' => 'paga',
        ])
        ->assertRedirect(route('invoices.show', $this->d['ids']['invoice']));

    $parcela = InvoiceInstallment::find($this->d['ids']['invoice_parcela_1']);
    expect($parcela->status)->toBe('paga')->and($parcela->payment_date->toDateString())->toBe('2026-11-09');
});

it('mudar a quantidade de parcelas na edição mantém as parcelas coerentes', function () {
    $this->actingAs($this->d['admin'])
        ->patch('/admin/faturas/' . $this->d['ids']['invoice'] . '/installments/' . $this->d['ids']['invoice_parcela_1'], [
            'amount' => 100, 'installments_count' => 5, 'due_date' => '2026-11-10', 'status' => 'pendente',
        ]);

    $fatura = Invoice::find($this->d['ids']['invoice']);
    expect($fatura->installments()->count())->toBe($fatura->installments_count);
});

it('admin exclui uma parcela, a fatura é recalculada e o boleto sai do disco', function () {
    $fatura = $this->d['ids']['invoice'];
    $parcela = $this->d['ids']['invoice_parcela_1'];
    $this->actingAs($this->d['admin']);

    $this->patch("/admin/faturas/$fatura/installments/$parcela/boleto", ['boleto' => pdfFalso()]);
    $boleto = InvoiceInstallment::find($parcela)->boleto_path;

    $this->delete("/admin/faturas/$fatura/installments/$parcela")
        ->assertRedirect(route('invoices.show', $fatura))
        ->assertSessionHas('success', 'Parcela excluída com sucesso!');

    $invoice = Invoice::find($fatura);
    expect(InvoiceInstallment::find($parcela))->toBeNull()
        ->and($invoice->installments_count)->toEqual(1)
        ->and((float) $invoice->total_amount)->toBe(100.0);
    Storage::disk('public')->assertMissing($boleto);
});

it('não exclui parcela de outra fatura', function () {
    $parcelaAlheia = $this->d['ids']['invoice_outro_parcela_1'];

    $this->actingAs($this->d['admin'])
        ->delete('/admin/faturas/' . $this->d['ids']['invoice'] . "/installments/$parcelaAlheia")
        ->assertNotFound();

    expect(InvoiceInstallment::find($parcelaAlheia))->not->toBeNull();
});

it('associado não exclui parcela', function () {
    $fatura = $this->d['ids']['invoice'];
    $parcela = $this->d['ids']['invoice_parcela_1'];

    $this->actingAs($this->d['membro'])
        ->delete("/admin/faturas/$fatura/installments/$parcela")
        ->assertForbidden();

    expect(InvoiceInstallment::find($parcela))->not->toBeNull();
});

it('método HTTP não suportado mostra a página de erro 405', function () {
    $this->actingAs($this->d['admin'])
        ->post('/admin/faturas/' . $this->d['ids']['invoice'] . '/installments/' . $this->d['ids']['invoice_parcela_1'])
        ->assertStatus(405)
        ->assertSee('Ação não permitida')
        ->assertSee('Voltar à Página Anterior');
});

it('fatura com todas as parcelas pagas aparece no filtro de pagas', function () {
    InvoiceInstallment::where('invoice_id', $this->d['ids']['invoice'])->update(['status' => 'paga', 'payment_date' => now()]);

    $this->actingAs($this->d['admin'])
        ->get('/admin/faturas?status=paga')
        ->assertOk()
        ->assertViewHas('invoices', fn ($faturas) => $faturas->pluck('id')->contains($this->d['ids']['invoice']));
});

it('o status da fatura acompanha as parcelas', function () {
    $fatura = Invoice::find($this->d['ids']['invoice']);
    expect($fatura->effective_status)->toBe('pendente');

    InvoiceInstallment::whereKey($this->d['ids']['invoice_parcela_1'])->update(['due_date' => now()->subDay()]);
    expect($fatura->fresh()->effective_status)->toBe('vencida');

    InvoiceInstallment::where('invoice_id', $fatura->id)->update(['status' => 'paga', 'payment_date' => now()]);
    expect($fatura->fresh()->effective_status)->toBe('paga');

    $this->actingAs($this->d['admin'])->get("/admin/faturas/{$fatura->id}")->assertOk()->assertSee('Paga');
});

it('membro vê apenas as próprias faturas', function () {
    $this->actingAs($this->d['membro'])
        ->get('/admin/faturas')
        ->assertOk()
        ->assertViewHas('invoices', fn ($faturas) => $faturas->pluck('user_id')->unique()->all() === [$this->d['membro']->id]);
});
