<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('a página de perfil é exibida', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('profile.edit'));

    $response->assertOk();
});

test('as informações do perfil podem ser atualizadas', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $response = $this
        ->actingAs($user)
        ->patch(route('profile.update'), [
            'name' => 'Usuário Teste',
            'email' => 'teste@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('profile.edit'));

    $user->refresh();

    $this->assertSame('Usuário Teste', $user->name);
    $this->assertSame('teste@example.com', $user->email);
    $this->assertNotNull($user->email_verified_at);
});

test('o status de verificação do email permanece inalterado quando o endereço de email não é alterado', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $response = $this
        ->actingAs($user)
        ->patch(route('profile.update'), [
            'name' => 'Usuário Teste',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('profile.edit'));

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('o usuário pode deletar sua conta', function () {
    $user = User::factory()->create(['password' => Hash::make('password')]);

    $response = $this
        ->actingAs($user)
        ->delete(route('profile.destroy'), [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('a senha correta deve ser fornecida para deletar a conta', function () {
    $user = User::factory()->create(['password' => Hash::make('password')]);

    $response = $this
        ->actingAs($user)
        ->from(route('profile.edit'))
        ->delete(route('profile.destroy'), [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrorsIn('userDeletion', ['password'])
        ->assertRedirect(route('profile.edit'));

    $this->assertNotNull($user->fresh());
});
