<?php

use Illuminate\Routing\Route as Rota;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
| Varredura de todas as rotas GET da aplicação, como visitante, membro e admin.
| Garante que nenhuma tela quebra com erro 500. Rotas novas entram sozinhas.
|
| Para gerar um relatório com o status de cada rota:
|   VARREDURA_RELATORIO=storage/logs/varredura.txt php artisan test --filter=TelasGet
*/

// Rotas que dependem de serviço externo ou não fazem sentido varrer
const ROTAS_IGNORADAS = ['google/redirect', 'google/callback'];

beforeEach(function () {
    Storage::fake('public');
    Storage::fake('local');
    $this->dados = criarDadosBase();
});

function rotasGetDaAplicacao(): array
{
    return collect(Route::getRoutes()->getRoutes())
        ->filter(fn (Rota $r) => in_array('GET', $r->methods()))
        ->filter(function (Rota $r) {
            $acao = $r->getActionName();
            return $acao === 'Closure' || str_starts_with($acao, 'App\\');
        })
        ->reject(fn (Rota $r) => in_array($r->uri(), ROTAS_IGNORADAS))
        ->values()
        ->all();
}

function urlDaRota(Rota $rota, array $ids, int $userId): string
{
    $uri = $rota->uri();

    return '/' . ltrim(preg_replace_callback('/\{(\w+)\??\}/', function ($m) use ($rota, $uri, $ids, $userId) {
        // Rotas que identificam o certificado pelo código impresso nele
        if ($m[1] === 'certificado' && $rota->bindingFieldFor('certificado') === 'token') {
            return \Illuminate\Support\Facades\DB::table('certificados')->where('id', $ids['certificado'])->value('token');
        }

        return match ($m[1]) {
            'user' => $userId,
            'hash' => 'hash-invalido',
            'token' => 'token-qualquer',
            'ano' => 2026,
            'id' => match (true) {
                str_starts_with($uri, 'admin/mensagens') => $ids['mensagem'],
                str_starts_with($uri, 'admin/submissions') => $ids['submission'],
                str_contains($uri, 'certificados') => $ids['certificado'],
                default => 1,
            },
            default => $ids[$m[1]] ?? 1,
        };
    }, $uri), '/');
}

it('nenhuma tela GET quebra com erro 500', function (string $ator) {
    $usuario = $ator === 'visitante' ? null : $this->dados[$ator];
    $falhas = [];
    $relatorio = [];

    foreach (rotasGetDaAplicacao() as $rota) {
        $url = urlDaRota($rota, $this->dados['ids'], $this->dados['membro']->id);

        $this->app['auth']->forgetGuards();
        if ($usuario) {
            $this->actingAs($usuario);
        }

        $resposta = $this->get($url);
        $status = $resposta->status();
        $erro = $resposta->exception ? class_basename($resposta->exception) . ': ' . $resposta->exception->getMessage() : '';

        if ($status >= 500) {
            $falhas[] = "$status $url — $erro";
        }

        $destino = $resposta->isRedirect() ? ' -> ' . parse_url($resposta->headers->get('Location'), PHP_URL_PATH) : '';
        $relatorio[] = sprintf('%-9s %d %s%s %s', $ator, $status, $url, $destino, $status >= 400 ? $erro : '');
    }

    if ($arquivo = env('VARREDURA_RELATORIO')) {
        file_put_contents(base_path($arquivo), implode(PHP_EOL, $relatorio) . PHP_EOL, FILE_APPEND);
    }

    expect($falhas)->toBeEmpty();
})->with(['visitante', 'membro', 'admin']);
