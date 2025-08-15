<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;

class AdminUserSeeder extends Seeder
{

    public function run(): void
    {
        $user = User::create([
            'name' => 'Frank',
            'sobrenome' => 'Administrador',
            'email' => 'admin@amaer.com.br',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'modalidade_principal' => 'aeromodelismo',
            'data_nascimento' => '1990-01-01',
            'cpf' => '12345678901',
            'rg' => '123456789',
            'telefone_celular' => '44999999999',
            'celular_whatsapp' => true,
            'telefone_residencial' => '4432221111',
            'logradouro' => 'Rua Principal',
            'numero' => '123',
            'bairro' => 'Centro',
            'cidade' => 'Paranavaí',
            'estado' => 'PR',
            'cep' => '87700000',
            'ativo' => true,
            'imagem' => 'default.png',
            'passwordGoogle' => null,
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Criar roles se não existirem
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $userRole = Role::firstOrCreate(['name' => 'User']);

        $user->assignRole($adminRole);
    }
}