<?php

use App\Models\InvoiceInstallment;

beforeEach(function () {
    $this->d = criarDadosBase();
});

it('admin dá baixa na parcela de um associado', function () {
    $parcela = $this->d['ids']['invoice_parcela_1'];

    $this->actingAs($this->d['admin'])
        ->patch("/admin/pendencias/installments/$parcela/baixar")
        ->assertRedirect()
        ->assertSessionHas('success');

    $p = InvoiceInstallment::find($parcela);
    expect($p->status)->toBe('paga')->and($p->payment_date->isToday())->toBeTrue();
});

it('membro vê apenas as próprias pendências e admin vê todas', function () {
    $this->actingAs($this->d['membro'])->get('/admin/pendencias')->assertOk()
        ->assertViewHas('installments', fn ($p) => $p->pluck('invoice.user_id')->unique()->all() === [$this->d['membro']->id]);

    $this->actingAs($this->d['admin'])->get('/admin/pendencias')->assertOk()
        ->assertViewHas('installments', fn ($p) => $p->count() === 4);
});

it('filtra pendências vencidas', function () {
    InvoiceInstallment::whereKey($this->d['ids']['invoice_parcela_1'])->update(['due_date' => now()->subDays(3)]);

    $this->actingAs($this->d['admin'])->get('/admin/pendencias?status=vencida')->assertOk()
        ->assertViewHas('installments', fn ($p) => $p->pluck('id')->all() === [$this->d['ids']['invoice_parcela_1']]);
});

it('admin busca pendências pelo nome do associado', function () {
    $this->actingAs($this->d['admin'])->get('/admin/pendencias?search=Outro')->assertOk()
        ->assertViewHas('installments', fn ($p) => $p->pluck('invoice.user_id')->unique()->all() === [$this->d['outro']->id]);
});

it('carregar mais pendências devolve JSON', function () {
    $this->actingAs($this->d['admin'])->getJson('/admin/pendencias/load-more?page=1')
        ->assertOk()
        ->assertJsonStructure(['html', 'hasMore']);
});

it('dashboard do admin soma as parcelas de todos', function () {
    $this->actingAs($this->d['admin'])->get('/admin')->assertOk()
        ->assertViewHas('qtdPendentes', 4)
        ->assertViewHas('totalPendente', 400.0)
        ->assertSee('Membros Ativos');
});

it('dashboard do membro mostra só as parcelas dele', function () {
    $this->actingAs($this->d['membro'])->get('/admin')->assertOk()
        ->assertViewHas('qtdPendentes', 2)
        ->assertViewHas('totalPendente', 200.0)
        ->assertDontSee('Outro Associado')
        ->assertDontSee('Membros Ativos')
        ->assertDontSee('Cadastrar Membro');
});

it('dashboard sem nenhuma fatura não quebra', function () {
    \App\Models\Invoice::query()->delete();

    $this->actingAs($this->d['membro'])->get('/admin')->assertOk()
        ->assertSee('Nenhum vencimento próximo.')
        ->assertSee('Nenhuma movimentação nos últimos 6 meses.');
});
