<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        // Crie permissões
        // Notícias, Contato,
        $permissions = [
            // Notícias
            'Criar Notícia',
            'Editar Notícia',
            'Visualizar Notícia',
            'Deletar Notícia',

            // Mensagens
            'Visualizar Mensagem',
            'Deletar Mensagem',
            'Marcar como Lida',
            'Marcar como Não Lida',
            'Alterar Status da Mensagem',

            // Lançamentos
            'Visualizar Lançamento',
            'Criar Lançamento',
            'Editar Lançamento',
            'Deletar Lançamento',
            'Filtrar lançamento',

            // Membros
            'Visualizar Membro',
            'Criar Membro',
            'Editar Membro',
            'Deletar Membro',

            // Parceiros
            'Visualizar Parceiro',
            'Criar Parceiro',
            'Editar Parceiro',
            'Deletar Parceiro',

            // Permissões
            'Visualizar Permissão',
            'Criar Permissão',
            'Editar Permissão',
            'Deletar Permissão',

            // Submissões
            'Visualizar Submissão',
            'Editar Submissão',
            'Deletar Submissão',

            // Projetos
            'Visualizar Projeto',
            'Criar Projeto',
            'Editar Projeto',
            'Deletar Projeto',
            'Criar tag',

            // Serviços
            'Visualizar Serviço',
            'Criar Serviço',
            'Editar Serviço',
            'Deletar Serviço',

            // Funções
            'Visualizar Função',
            'Criar Função',
            'Editar Função',
            'Deletar Função',

            // Certificados
            'Visualizar Certificado',
            'Criar Certificado',
            'Editar Certificado',
            'Deletar Certificado',
            
            // Frases
            'Criar Frase',

            // Galeria
            'Visualizar Galeria',
            'Criar Galeria',
            'Editar Galeria',
            'Deletar Galeria',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crie cargos e atribua permissões
        $roles = [
            'Membro' => [
                'Visualizar Lançamento', 
                'Criar Lançamento', 
                'Editar Lançamento', 
                'Visualizar Projeto', 
                'Visualizar Serviço'
            ],
            'Admin' => $permissions
        ];

        foreach ($roles as $role => $rolePermissions) {
            $role = Role::create(['name' => $role]);
            $role->givePermissionTo($rolePermissions);
        }
    }
}
