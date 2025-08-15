<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;

// Criar role admin se não existir
if (!Role::where('name', 'admin')->exists()) {
    Role::create(['name' => 'admin']);
    echo "Role 'admin' criada.\n";
} else {
    echo "Role 'admin' já existe.\n";
}

// Criar usuário admin se não existir
$admin = User::where('email', 'admin@admin.com')->first();

if (!$admin) {
    $admin = User::create([
        'name' => 'Administrador',
        'email' => 'admin@admin.com',
        'password' => bcrypt('123456'),
        'cpf' => '00000000000',
        'email_verified_at' => now(),
    ]);
    echo "Usuário admin criado.\n";
} else {
    echo "Usuário admin já existe.\n";
}

// Atribuir role admin
if (!$admin->hasRole('admin')) {
    $admin->assignRole('admin');
    echo "Role 'admin' atribuída ao usuário.\n";
} else {
    echo "Usuário já tem role 'admin'.\n";
}

echo "\nCredenciais do admin:\n";
echo "Email: admin@admin.com\n";
echo "Senha: 123456\n";
