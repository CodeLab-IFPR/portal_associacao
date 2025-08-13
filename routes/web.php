<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GaleriaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjetoController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\NoticiasController;
use App\Http\Controllers\ParceiroController;
use App\Http\Controllers\PermissaoController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\Auth\GoogleLoginController;
use App\Http\Controllers\Admin\FraseInicioController;
use App\Http\Controllers\LancamentoServicoController;
use App\Http\Controllers\Auth\RegisteredUserController;

// Rotas Públicas
Route::get('/', function () {
    $noticias = (new NoticiasController)->home()->getData()['noticias'];
    $projetos = (new ProjetoController)->home()->getData()['projetos'];
    return view('home', compact('noticias', 'projetos'));
})->name('home');
Route::get('/sobre', function () { return view('about'); })->name('about');
Route::get('/contato', function () { return view('contact'); })->name('contact');
Route::get('/submission', function () { return view('submission'); })->name('submission');
Route::get('/about', [RegisteredUserController::class, 'about'])->name('about');
Route::get('/projetos/cards', [ProjetoController::class, 'indexPublic'])->name('projeto.indexPublic');

// Rota pública de cards de notícias
Route::get('/noticias/cards', [NoticiasController::class, 'cards'])->name('noticias.cards');
Route::get('/noticias/{noticia}', [NoticiasController::class, 'show'])->name('noticias.show');

// Rotas públicas de certificados
Route::get('/certificados/emitir', [CertificadoController::class, 'emitir'])->name('certificados.emitir');
Route::post('/certificados/buscar', [CertificadoController::class, 'buscarCertificados'])->name('certificados.buscar');
Route::get('/certificados/{certificado}/view', [CertificadoController::class, 'viewCertificate'])->name('certificados.view');
Route::get('/certificados/{certificado}/download', [CertificadoController::class, 'downloadCertificate'])->name('certificados.download');
Route::get('/certificados/validar', [CertificadoController::class, 'showValidationForm'])->name('certificados.validar');
Route::post('/certificados/validar', [CertificadoController::class, 'validarCertificado'])->name('certificados.validar.post');

// Rotas para submissão de formulários públicos
Route::post('/submit-demand', [SubmissionController::class, 'submit'])->name('submit-demand');
Route::post('/send-message', [ContactController::class, 'sendMessage'])->name('send-message');
Route::post('/submit', [SubmissionController::class, 'submit'])->name('submission.submit');

Route::resource('certificados', CertificadoController::class);
Route::get('certificados/{id}/download', [CertificadoController::class, 'download'])->name('certificados.download');
Route::get('/certificados/{certificado}/view', [CertificadoController::class, 'viewCertificate'])->name('certificados.view');
Route::delete('certificados/{certificado}', [CertificadoController::class, 'destroy'])->name('certificados.destroy');
Route::post('/certificados/generate', [CertificadoController::class, 'generateFromTasks'])->name('certificados.generate');
Route::get('/certificados/create', [CertificadoController::class, 'create'])->name('certificados.create');
Route::post('/certificados', [CertificadoController::class, 'store'])->name('certificados.store');

// Rotas de Autenticação Google
Route::get('/google/redirect', [GoogleLoginController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/google/callback', [GoogleLoginController::class, 'handleGoogleCallback'])->name('google.callback');

// Public gallery routes
Route::get('/galeria', [GaleriaController::class, 'index'])->name('galeria.public');
Route::get('/galeria/ano/{ano}', [GaleriaController::class, 'peloAno'])->name('galeria.ano');
Route::get('/galeria', [GaleriaController::class, 'indexPublic'])->name('galeria.indexPublic');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('frase-inicio/editar', [App\Http\Controllers\Admin\FraseInicioController::class, 'editar'])->name('frase_inicio.editar');
    Route::put('frase-inicio/atualizar', [App\Http\Controllers\Admin\FraseInicioController::class, 'atualizar'])->name('frase_inicio.atualizar');
});

// Rotas Administrativas
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    // Rota principal do admin (dashboard)
    Route::get('/', function () {
        return view('admin.index');
    })->name('admin');

    // Rotas da galeria (admin)
    Route::group(['prefix' => 'galeria'], function () {
        Route::get('/', [GaleriaController::class, 'indexAdmin'])->name('galeria.indexAdmin');
        Route::get('/create', [GaleriaController::class, 'create'])->name('galeria.create');
        Route::post('/', [GaleriaController::class, 'store'])->name('galeria.store');
        Route::get('/{galeria}/edit', [GaleriaController::class, 'edit'])->name('galeria.edit');
        Route::put('/{galeria}', [GaleriaController::class, 'update'])->name('galeria.update');
        Route::delete('/{galeria}', [GaleriaController::class, 'destroy'])->name('galeria.destroy');
    });

    // Rotas de certificados (admin)
    Route::resource('certificados', CertificadoController::class)->except(['show']);
    Route::post('/certificados/generate', [CertificadoController::class, 'generateFromTasks'])->name('certificados.generate');

    // Rotas de usuários
    Route::resource('users', RegisteredUserController::class);

    // Rotas de permissões
    Route::group(['prefix' => 'permissoes'], function () {
        Route::get('/', [PermissaoController::class, 'index'])->name('permissoes.index');
        Route::get('/create', [PermissaoController::class, 'create'])->name('permissoes.create');
        Route::post('/', [PermissaoController::class, 'store'])->name('permissoes.store');
        Route::get('/{permissao}/edit', [PermissaoController::class, 'edit'])->name('permissoes.edit');
        Route::put('/{permissao}', [PermissaoController::class, 'update'])->name('permissoes.update');
        Route::delete('/{permissao}', [PermissaoController::class, 'destroy'])->name('permissoes.destroy');
    });
    
    // Rotas manuais para funções
    Route::group(['prefix' => 'funcoes'], function () {
        Route::get('/', [RoleController::class, 'index'])->name('funcoes.index');
        Route::get('/create', [RoleController::class, 'create'])->name('funcoes.create');
        Route::post('/', [RoleController::class, 'store'])->name('funcoes.store');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('funcoes.edit');
        Route::put('/{role}', [RoleController::class, 'update'])->name('funcoes.update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('funcoes.destroy');
    });

    // Outras rotas administrativas
    Route::resource('projetos', ProjetoController::class);
    Route::resource('servicos', ServicoController::class);
    Route::resource('tags', TagController::class);
    
    Route::group(['prefix' => 'lancamentos'], function(){
        Route::get('/', [LancamentoServicoController::class, 'index'])->name('lancamentos.index');
        Route::get('/create', [LancamentoServicoController::class, 'create'])->name('lancamentos.create');
        Route::post('/', [LancamentoServicoController::class, 'store'])->name('lancamentos.store');
        Route::get('/{lancamento}/edit', [LancamentoServicoController::class, 'edit'])->name('lancamentos.edit');
        Route::put('/{lancamento}', [LancamentoServicoController::class, 'update'])->name('lancamentos.update');
        Route::delete('/{lancamento}', [LancamentoServicoController::class, 'destroy'])->name('lancamentos.destroy');
        Route::post('/lancamentos/generate-certificates', [LancamentoServicoController::class, 'generateCertificates'])->name('lancamentos.generateCertificates');
    });
    
    Route::resource('parceiros', ParceiroController::class);

    // Rotas de notícias (admin) - removido 'cards' da resource
    Route::group(['prefix' => 'noticias'], function(){
        Route::get('/', [NoticiasController::class, 'index'])->name('noticias.index');
        Route::get('/create', [NoticiasController::class, 'create'])->name('noticias.create');
        Route::post('/', [NoticiasController::class, 'store'])->name('noticias.store');
        Route::get('/{noticia}/edit', [NoticiasController::class, 'edit'])->name('noticias.edit');
        Route::put('/{noticia}', [NoticiasController::class, 'update'])->name('noticias.update');
        Route::delete('/{noticia}', [NoticiasController::class, 'destroy'])->name('noticias.destroy');
    });

    // Rotas de submissões
    Route::controller(SubmissionController::class)->group(function () {
        Route::get('/submissions', 'index')->name('submissions.index');
        Route::get('/submissions/{id}', 'show')->name('submissions.show');
        Route::post('/submissions/{id}/mark-read', 'markRead')->name('submissions.markRead');
        Route::post('/submissions/{id}/mark-unread', 'markUnread')->name('submissions.markUnread');
        Route::post('/submissions/{id}/toggleRead', 'toggleRead')->name('submissions.toggleRead');
        Route::post('/submissions/markReadSelected', 'markReadSelected')->name('submissions.markReadSelected');
        Route::post('/submissions/markUnreadSelected', 'markUnreadSelected')->name('submissions.markUnreadSelected');
        Route::delete('/submissions/deleteSelected', 'deleteSelected')->name('submissions.deleteSelected');
        Route::delete('/submissions/{id}', 'destroy')->name('submissions.destroy');
    });

    Route::get('/mensagens', [ContactController::class, 'index'])->name('mensagens.index');
    Route::get('/mensagens/{id}', [ContactController::class, 'show'])->name('mensagens.show');
    Route::delete('/mensagens/deleteSelected', [ContactController::class, 'deleteSelected'])->name('mensagens.deleteSelected');
    Route::post('/mensagens/{id}/mark-read', [ContactController::class, 'markRead'])->name('mensagens.markRead');
    Route::delete('/mensagens/{id}', [ContactController::class, 'destroy'])->name('mensagens.destroy');
    Route::post('/mensagens/{id}/mark-unread', [ContactController::class, 'markUnread'])->name('mensagens.markUnread');
    Route::post('/mensagens/{id}/toggleRead', [ContactController::class, 'toggleRead'])->name('mensagens.toggleRead');
    Route::post('/mensagens/markReadSelected', [ContactController::class, 'markReadSelected'])->name('mensagens.markReadSelected');
    Route::post('/mensagens/markUnreadSelected', [ContactController::class, 'markUnreadSelected'])->name('mensagens.markUnreadSelected');

    // Admin gallery routes (protected)
    Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
        Route::resource('galeria', GaleriaController::class)->names([
            'index' => 'galeria.index',
            'create' => 'galeria.create',
            'store' => 'galeria.store',
            'edit' => 'galeria.edit',
            'update' => 'galeria.update',
            'destroy' => 'galeria.destroy'
        ]);
    });

    // Rotas de perfil
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/perfil', 'edit')->name('profile.edit');
        Route::patch('/perfil', 'update')->name('profile.update');
        Route::delete('/perfil', 'destroy')->name('profile.destroy');
    });

    // Frase Início
    Route::get('frase-inicio/editar', [FraseInicioController::class, 'editar'])->name('admin.frase_inicio.editar');
    Route::put('frase-inicio/atualizar', [FraseInicioController::class, 'atualizar'])->name('admin.frase_inicio.atualizar');
    Route::post('lancamentos/generate-certificates', [LancamentoServicoController::class, 'generateCertificates'])->name('lancamentos.generateCertificates');
});

require __DIR__.'/auth.php';