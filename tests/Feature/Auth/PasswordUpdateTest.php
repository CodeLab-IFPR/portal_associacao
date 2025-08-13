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

    $response
        ->assertSessionHasErrors(['current_password'])
        ->assertRedirect('/profile');

    $this->assertTrue(session()->has('errors'));
    $this->assertNotEmpty(session('errors')->get('current_password'));
});

