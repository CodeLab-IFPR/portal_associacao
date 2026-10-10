<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    $this->d = criarDadosBase();
});

function dadosNovoUsuario(array $extra = []): array
{
    return $extra + [
        'nome' => 'Maria', 'sobrenome' => 'Souza', 'email' => 'maria@example.com',
        'cpf' => '52998224725', 'generated_password' => 'Senha@123', 'roles' => ['Membro'],
        'profissao' => 'Engenheira', 'escolaridade' => 'Ensino Superior',
    ];
}

// ── Cadastro de membros pelo admin ───────────────────────────────

it('admin cadastra membro com papel, profissão e escolaridade', function () {
    Bus::fake();

    $this->actingAs($this->d['admin'])
        ->post('/admin/users', dadosNovoUsuario())
        ->assertRedirect(route('users.index'));

    $user = User::where('email', 'maria@example.com')->firstOrFail();
    expect($user->name)->toBe('Maria Souza')
        ->and($user->hasRole('Membro'))->toBeTrue()
        ->and($user->profissao)->toBe('Engenheira')
        ->and($user->escolaridade)->toBe('Ensino Superior')
        ->and(Hash::check('Senha@123', $user->password))->toBeTrue();
    Bus::assertDispatched(\App\Jobs\SendPasswordJob::class);
});

it('envia o e-mail de verificação uma única vez ao cadastrar membro', function () {
    Bus::fake();
    Event::fake([Registered::class]);

    $this->actingAs($this->d['admin'])->post('/admin/users', dadosNovoUsuario());

    Event::assertDispatchedTimes(Registered::class, 1);
});

it('valida o cadastro de membro pelo admin', function (array $sobrescrever, string $campo) {
    Bus::fake();

    $this->actingAs($this->d['admin'])
        ->post('/admin/users', array_merge(dadosNovoUsuario(), $sobrescrever))
        ->assertSessionHasErrors($campo);
})->with([
    'escolaridade fora da lista' => [['escolaridade' => 'Técnico'], 'escolaridade'],
    'e-mail repetido' => [['email' => 'MEMBRO@EXAMPLE.COM'], 'email'],
    'CPF repetido' => [fn () => ['cpf' => test()->d['membro']->cpf], 'cpf'],
    'sem CPF' => [['cpf' => ''], 'cpf'],
]);

it('admin edita membro e troca o papel', function () {
    $membro = $this->d['membro'];

    $this->actingAs($this->d['admin'])
        ->put("/admin/users/{$membro->id}", [
            'name' => 'Membro Editado', 'email' => $membro->email, 'cpf' => $membro->cpf,
            'roles' => ['Admin'], 'escolaridade' => 'Mestrado',
        ])
        ->assertRedirect();

    $membro->refresh();
    expect($membro->escolaridade)->toBe('Mestrado')->and($membro->hasRole('Admin'))->toBeTrue();
});

it('admin exclui membro', function () {
    $this->actingAs($this->d['admin'])
        ->delete('/admin/users/' . $this->d['outro']->id)
        ->assertRedirect(route('users.index'));

    expect(User::find($this->d['outro']->id))->toBeNull();
});

// ── Perfil do próprio usuário ────────────────────────────────────

it('membro atualiza o próprio perfil com profissão e escolaridade', function () {
    $membro = $this->d['membro'];

    $this->actingAs($membro)
        ->patch('/admin/perfil', ['name' => 'Novo Nome', 'email' => $membro->email, 'profissao' => 'Piloto', 'escolaridade' => 'Doutorado'])
        ->assertRedirect(route('profile.edit'));

    $membro->refresh();
    expect($membro->name)->toBe('Novo Nome')->and($membro->profissao)->toBe('Piloto')->and($membro->escolaridade)->toBe('Doutorado');
});

it('perfil rejeita escolaridade fora da lista', function () {
    $this->actingAs($this->d['membro'])
        ->patch('/admin/perfil', ['name' => 'x', 'email' => $this->d['membro']->email, 'escolaridade' => 'Técnico'])
        ->assertSessionHasErrors('escolaridade');
});

it('trocar o e-mail no perfil exige nova verificação', function () {
    $this->actingAs($this->d['membro'])
        ->patch('/admin/perfil', ['name' => 'x', 'email' => 'novo-email@example.com']);

    expect($this->d['membro']->fresh()->email_verified_at)->toBeNull();
});

it('membro não consegue usar no perfil o CPF de outro associado', function () {
    $resposta = $this->actingAs($this->d['membro'])
        ->patch('/admin/perfil', ['name' => 'x', 'email' => $this->d['membro']->email, 'cpf' => $this->d['outro']->cpf]);

    expect($resposta->status())->toBeLessThan(500, 'Erro 500: ' . $resposta->exception?->getMessage());
    $resposta->assertSessionHasErrors('cpf');
});

it('membro troca a senha informando a senha atual', function () {
    $this->actingAs($this->d['membro'])
        ->put('/admin/alterar-senha', ['current_password' => 'password', 'password' => 'NovaSenha@123', 'password_confirmation' => 'NovaSenha@123'])
        ->assertSessionHasNoErrors();

    expect(Hash::check('NovaSenha@123', $this->d['membro']->fresh()->password))->toBeTrue();
});

it('troca de senha com senha atual errada é recusada', function () {
    $this->actingAs($this->d['membro'])
        ->put('/admin/alterar-senha', ['current_password' => 'errada', 'password' => 'NovaSenha@123', 'password_confirmation' => 'NovaSenha@123'])
        ->assertSessionHasErrorsIn('updatePassword', 'current_password');

    expect(Hash::check('password', $this->d['membro']->fresh()->password))->toBeTrue();
});

it('membro atualiza a foto do perfil', function () {
    usarPublicTemporario();
    $imagem = \Illuminate\Http\UploadedFile::fake()->image('f.png', 50, 50);
    $png = 'data:image/png;base64,' . base64_encode(file_get_contents($imagem->getRealPath()));

    $this->actingAs($this->d['membro'])
        ->patch('/admin/perfil/imagem', ['cropped_image' => $png])
        ->assertRedirect(route('profile.edit'))
        ->assertSessionHas('status', 'profile-image-updated');

    $imagem = $this->d['membro']->fresh()->imagem;
    expect($imagem)->not->toBeNull()->and(file_exists(public_path("imagens/users/$imagem")))->toBeTrue();
});

// ── Cadastro público e acesso ────────────────────────────────────

function dadosCadastroPublico(array $extra = []): array
{
    return $extra + [
        'modalidade_principal' => 'aeromodelismo', 'nome' => 'João', 'sobrenome' => 'Lima',
        'cpf' => '111.444.777-35', 'rg' => '123456', 'telefone_celular' => '(43) 99999-0000',
        'email' => 'joao@example.com', 'cep' => '86000-000', 'logradouro' => 'Rua A', 'numero' => '10',
        'bairro' => 'Centro', 'estado' => 'PR', 'cidade' => 'Londrina',
    ];
}

it('visitante se cadastra como associado inativo com papel Membro', function () {
    Mail::fake();

    $this->post('/register', dadosCadastroPublico())
        ->assertRedirect(route('member.register.form'))
        ->assertSessionHas('success');

    $user = User::where('email', 'joao@example.com')->firstOrFail();
    expect($user->cpf)->toBe('11144477735')
        ->and($user->name)->toBe('João Lima')
        ->and((bool) $user->ativo)->toBeFalse()
        ->and($user->hasRole('Membro'))->toBeTrue();
    Mail::assertSent(\App\Mail\MemberRegistrationMail::class);
});

it('cadastro público recusa CPF inválido', function () {
    Mail::fake();

    $this->post('/register', dadosCadastroPublico(['cpf' => '111.111.111-11']))->assertSessionHasErrors('cpf');
});

it('associado inativo (ainda não aprovado) não consegue entrar', function () {
    $inativo = User::factory()->create(['ativo' => false, 'password' => Hash::make('Senha@123')]);
    $inativo->assignRole('Membro');

    $this->post('/login', ['email' => $inativo->email, 'password' => 'Senha@123']);

    $this->assertGuest();
});

// ── Cartão do associado ──────────────────────────────────────────

it('membro abre o próprio cartão', function () {
    $this->actingAs($this->d['membro'])->get('/admin/cartao-associado')->assertOk()->assertSee('Membro Teste');
});

it('QR code do cartão valida o associado', function () {
    $m = $this->d['membro'];

    $this->get("/validar-cartao/{$m->id}/" . md5($m->email . $m->created_at))
        ->assertOk()->assertViewHas('resultado', 'valido')->assertSee('Membro Teste');
});

it('hash inválido não valida nem expõe dados do associado', function () {
    $this->get('/validar-cartao/' . $this->d['membro']->id . '/qualquer-coisa')
        ->assertOk()->assertViewHas('resultado', 'invalido')->assertDontSee('Membro Teste');
});

it('validação pela assinatura de 12 caracteres', function () {
    $m = $this->d['membro'];

    $this->post('/validar', ['assinatura' => substr(md5($m->email . $m->created_at), 0, 12)])
        ->assertOk()->assertViewHas('resultado', 'valido');
});

it('cartão de associado inativo não aparece como ativo', function () {
    $m = $this->d['membro'];
    $m->update(['ativo' => false]);

    $this->get("/validar-cartao/{$m->id}/" . md5($m->email . $m->fresh()->created_at))
        ->assertOk()->assertDontSee('>Ativo<', false);
});
