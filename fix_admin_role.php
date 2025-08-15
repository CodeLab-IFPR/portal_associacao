<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;

// Buscar usuário admin
$admin = User::where('email', 'admin@admin.com')->first();

if ($admin) {
    // Remover role "Admin" (maiúsculo)
    if ($admin->hasRole('Admin')) {
        $admin->removeRole('Admin');
        echo "Role 'Admin' removida.\n";
    }
    
    // Atribuir role "admin" (minúsculo)
    if (!$admin->hasRole('admin')) {
        $admin->assignRole('admin');
        echo "Role 'admin' atribuída.\n";
    }
    
    echo "✅ Usuário admin configurado corretamente!\n";
    echo "Roles atuais: " . implode(', ', $admin->getRoleNames()->toArray()) . "\n";
} else {
    echo "❌ Usuário admin não encontrado!\n";
}
