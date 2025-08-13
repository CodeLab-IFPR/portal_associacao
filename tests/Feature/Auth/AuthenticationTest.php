<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('a tela de login pode ser renderizada', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('usuários podem se autenticar usando a tela de login', function () {
    $user = User::factory()->create([
        'name' => 'Usuário Teste',
        'email' => 'teste@exemplo.com',
        'cpf' => '12345678901',
        'password' => Hash::make('password')
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('admin', absolute: false));
});

test('usuários não podem se autenticar com senha inválida', function () {
    $user = User::factory()->create([
        'name' => 'Usuário Teste',
        'email' => 'teste@exemplo.com',
        'cpf' => '12345678901',
        'password' => Hash::make('password')
    ]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'senha-errada',
    ]);

    $this->assertGuest();
});

test('usuários podem fazer logout', function () {
    $user = User::factory()->create([
        'name' => 'Usuário Teste',
        'email' => 'teste@example.com',
        'cpf' => '12345678901',
        'password' => Hash::make('password')
    ]);

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
