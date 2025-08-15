<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FraseInicio;

class FraseInicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpar registros existentes
        FraseInicio::truncate();

        // Criar frase de início principal
        FraseInicio::create([
            'frase' => 'Bem-vindos à AMAER - Associação de Aeromodelismo e Automodelismo de Maringá',
            'titulo' => 'AMAER',
            'subtitulo' => 'Associação de Aeromodelismo e Automodelismo',
            'localizacao' => 'Maringá - PR',
            'descricao' => 'Somos uma associação dedicada aos amantes do aeromodelismo e automodelismo, promovendo o desenvolvimento técnico, cultural e social através do modelismo esportivo. Venha fazer parte da nossa comunidade e descobrir a paixão pelos modelos em miniatura.',
            'como_associar_se' => 'Para se associar à AMAER, entre em contato conosco através do formulário de contato, WhatsApp ou visite nossa sede. Nossa equipe está pronta para orientá-lo sobre os requisitos, modalidades disponíveis e benefícios de ser um membro da nossa associação. Junte-se a nós e participe de competições, workshops e eventos exclusivos para associados.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        echo "Seeder FraseInicio executado com sucesso!\n";
    }
}
