<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('a tela de confirmação de senha pode ser renderizada', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/confirm-password');

    $response->assertStatus(200);
});

test('a senha pode ser confirmada', function () {
    $user = User::factory()->create(['password' => Hash::make('Password1!')]);

    $response = $this->actingAs($user)->post('/confirm-password', [
        'password' => 'Password1!',
    ]);

    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
});

test('a senha não é confirmada com senha inválida', function () {
    $user = User::factory()->create(['password' => Hash::make('Password1!')]);

    $response = $this->actingAs($user)->post('/confirm-password', [
        'password' => 'WrongPassword1!',
    ]);

    $response->assertSessionHasErrors();
});
