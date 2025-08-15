<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Spatie\Permission\Models\Permission;

Route::get('/grant-permissions', function () {
    try {
        // Buscar o usuário admin (primeiro usuário)
        $admin = User::find(1);
        
        if (!$admin) {
            return 'Usuário admin não encontrado!';
        }

        // Criar permissões se não existirem
        $permissions = [
            'Visualizar Documento',
            'Criar Documento', 
            'Editar Documento',
            'Excluir Documento',
            'Aprovar Documento'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Conceder permissões ao admin
        $admin->givePermissionTo($permissions);

        return 'Permissões concedidas com sucesso ao usuário: ' . $admin->name;
        
    } catch (Exception $e) {
        return 'Erro: ' . $e->getMessage();
    }
});
