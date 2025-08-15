<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Documento;
use Spatie\Permission\Models\Role;

echo "=== DEBUG ADMIN ===\n\n";

// Verificar usuário admin
$admin = User::where('email', 'admin@admin.com')->first();
if ($admin) {
    echo "✅ Usuário admin encontrado:\n";
    echo "   ID: {$admin->id}\n";
    echo "   Nome: {$admin->name}\n";
    echo "   Email: {$admin->email}\n";
    echo "   Roles: " . implode(', ', $admin->getRoleNames()->toArray()) . "\n";
    echo "   hasRole('admin'): " . ($admin->hasRole('admin') ? 'SIM' : 'NÃO') . "\n\n";
} else {
    echo "❌ Usuário admin NÃO encontrado!\n\n";
}

// Verificar roles disponíveis
echo "=== ROLES DISPONÍVEIS ===\n";
$roles = Role::all();
foreach ($roles as $role) {
    echo "- {$role->name}\n";
}
echo "\n";

// Verificar documentos
echo "=== DOCUMENTOS CADASTRADOS ===\n";
$documentos = Documento::with('user')->get();
if ($documentos->count() > 0) {
    foreach ($documentos as $doc) {
        echo "- ID: {$doc->id} | {$doc->nome_original} | Usuário: {$doc->user->name} | Status: {$doc->status}\n";
    }
} else {
    echo "❌ Nenhum documento encontrado!\n";
}
echo "\n";

// Verificar se admin veria os documentos
if ($admin && $admin->hasRole('admin')) {
    $adminDocs = Documento::with(['user', 'aprovador'])
        ->orderBy('created_at', 'desc')
        ->get();
    echo "=== DOCUMENTOS QUE ADMIN DEVERIA VER ===\n";
    if ($adminDocs->count() > 0) {
        foreach ($adminDocs as $doc) {
            echo "- ID: {$doc->id} | {$doc->nome_original} | Usuário: {$doc->user->name}\n";
        }
    } else {
        echo "❌ Admin não veria nenhum documento!\n";
    }
}
