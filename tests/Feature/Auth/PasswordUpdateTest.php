<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('a senha pode ser atualizada', function () {
    $user = User::factory()->create(['password' => Hash::make('Password1!')]);

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->put('/password', [
            'current_password' => 'Password1!',
            'password' => 'NewPassword1!',
            'password_confirmation' => 'NewPassword1!',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $this->assertTrue(Hash::check('NewPassword1!', $user->refresh()->password));
});

test('a senha correta deve ser fornecida para atualizar a senha', function () {
    $user = User::factory()->create(['password' => Hash::make('Password1!')]);

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->put('/password', [
            'current_password' => 'WrongPassword1!',
            'password' => 'NewPassword1!',
            'password_confirmation' => 'NewPassword1!',
        ]);

    // O PasswordController grava os erros no bag "updatePassword"
    $response
        ->assertSessionHasErrorsIn('updatePassword', ['current_password'])
        ->assertRedirect('/profile');

    $this->assertFalse(Hash::check('NewPassword1!', $user->refresh()->password));
});

