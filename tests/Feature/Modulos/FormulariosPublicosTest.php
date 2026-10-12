<?php

use App\Models\Contact;
use App\Models\Submission;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
    usarPublicTemporario();
});

// ── Fale conosco ─────────────────────────────────────────────────

it('visitante envia mensagem pelo fale conosco', function () {
    $this->from('/contato')
        ->post('/send-message', ['name' => 'Carlos', 'email' => 'carlos@example.com', 'message' => 'Quero me associar'])
        ->assertRedirect('/contato')
        ->assertSessionHas('success');

    expect(Contact::where('email', 'carlos@example.com')->exists())->toBeTrue();
});

it('fale conosco valida os campos em vez de quebrar', function (array $dados, string $campo) {
    $resposta = $this->from('/contato')->post('/send-message', $dados);

    expect($resposta->status())->toBeLessThan(500, 'Erro 500: ' . $resposta->exception?->getMessage());
    $resposta->assertSessionHasErrors($campo);
    expect(Contact::count())->toBe(0);
})->with([
    'sem nome' => [['email' => 'a@example.com', 'message' => 'Olá'], 'name'],
    'sem e-mail' => [['name' => 'A', 'message' => 'Olá'], 'email'],
    'e-mail inválido' => [['name' => 'A', 'email' => 'nao-e-email', 'message' => 'Olá'], 'email'],
    'sem mensagem' => [['name' => 'A', 'email' => 'a@example.com'], 'message'],
]);

it('fale conosco limita envios repetidos (anti-spam)', function () {
    foreach (range(1, 15) as $i) {
        $ultima = $this->post('/send-message', ['name' => 'Spam', 'email' => "spam$i@example.com", 'message' => 'Compre já']);
    }

    expect($ultima->status())->toBe(429);
});

it('fale conosco não anexa arquivos do servidor indicados pelo visitante', function () {
    $this->post('/send-message', [
        'name' => 'Atacante', 'email' => 'x@example.com', 'message' => 'Oi',
        'attachments' => json_encode(['../.env']),
    ]);

    Mail::assertSent(\App\Mail\ContactMessage::class, fn ($mail) => empty($mail->data['attachments']));
});

// ── Submissão de demanda ─────────────────────────────────────────

it('submissão não anexa arquivos do servidor indicados pelo visitante', function () {
    $this->post('/submit-demand', [
        'name' => 'Atacante', 'email' => 'x@example.com', 'demand_description' => 'x', 'expected_utility' => 'x',
        'attachments' => json_encode(['../.env']),
    ]);

    Mail::assertSent(\App\Mail\DemandSubmission::class, fn ($mail) => empty($mail->data['attachments']));
    expect(Submission::first()?->attachments)->toBeNull();
});

it('visitante envia demanda com anexo PDF', function () {
    $this->from('/submission')
        ->post('/submit-demand', [
            'name' => 'Empresa X', 'email' => 'contato@empresa.com',
            'demand_description' => 'Precisamos de um drone', 'expected_utility' => 'Mapeamento',
            'supporting_files' => [pdfFalso('proposta.pdf')],
        ])
        ->assertRedirect('/submission')
        ->assertSessionHas('success');

    $anexos = json_decode(Submission::firstOrFail()->attachments, true);
    expect($anexos)->toHaveCount(1)->and(file_exists(public_path($anexos[0])))->toBeTrue();
});

it('submissão valida os campos em vez de quebrar', function () {
    $resposta = $this->from('/submission')->post('/submit-demand', ['name' => 'Sem e-mail']);

    expect($resposta->status())->toBeLessThan(500, 'Erro 500: ' . $resposta->exception?->getMessage());
    $resposta->assertSessionHasErrors(['email', 'demand_description', 'expected_utility']);
});

it('submissão não aceita arquivo executável (.php) na pasta pública', function () {
    $this->post('/submit-demand', [
        'name' => 'Atacante', 'email' => 'x@example.com', 'demand_description' => 'x', 'expected_utility' => 'x',
        'supporting_files' => [UploadedFile::fake()->createWithContent('shell.php', '<?php echo "invadido"; ?>')],
    ]);

    $phpPublicos = glob(public_path('anexos_submissions/*.php'));
    expect($phpPublicos)->toBeEmpty('Arquivo .php foi salvo em pasta pública: ' . implode(', ', array_map('basename', $phpPublicos)));
});

it('fale conosco e demanda mostram a confirmação na tela', function () {
    $this->from('/contato')->followingRedirects()
        ->post('/send-message', ['name' => 'Ana', 'email' => 'ana@example.com', 'message' => 'Olá'])
        ->assertSee('Mensagem enviada com sucesso!');

    $this->from('/submission')->followingRedirects()
        ->post('/submit-demand', ['name' => 'Ana', 'email' => 'ana@example.com', 'demand_description' => 'x', 'expected_utility' => 'y'])
        ->assertSee('Submissão enviada com sucesso!');
});

it('fale conosco mostra os erros de validação na tela', function () {
    $this->from('/contato')->followingRedirects()
        ->post('/send-message', ['email' => 'ana@example.com'])
        ->assertSee('Informe seu nome.')
        ->assertSee('Escreva sua mensagem.');
});
