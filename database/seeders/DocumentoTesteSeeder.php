<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Documento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DocumentoTesteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar os primeiros 3 usuários
        $users = User::limit(3)->get();

        foreach ($users as $user) {
            // Criar 2-3 documentos para cada usuário
            for ($i = 1; $i <= rand(2, 3); $i++) {
                $tiposDocumento = ['RG', 'CPF', 'Comprovante de Residência', 'Certificado', 'Contrato'];
                $statusOptions = ['pendente', 'aprovado', 'rejeitado'];
                $status = $statusOptions[array_rand($statusOptions)];
                
                $documento = Documento::create([
                    'user_id' => $user->id,
                    'nome_arquivo' => 'documento_' . $user->id . '_' . $i . '.pdf',
                    'nome_original' => $tiposDocumento[array_rand($tiposDocumento)] . '_' . $user->name . '.pdf',
                    'caminho_arquivo' => 'documentos/' . $user->id . '/documento_' . $i . '.pdf',
                    'tipo_documento' => $tiposDocumento[array_rand($tiposDocumento)],
                    'descricao' => 'Documento ' . $i . ' enviado pelo usuário ' . $user->name,
                    'status' => $status,
                    'aprovado_por' => $status !== 'pendente' ? 1 : null, // Assumindo que o admin tem ID 1
                    'aprovado_em' => $status !== 'pendente' ? now()->subDays(rand(1, 30)) : null,
                    'observacoes' => $status === 'rejeitado' ? 'Documento não atende aos critérios estabelecidos.' : null,
                ]);

                // Criar diretório se não existir
                $dir = dirname(storage_path('app/' . $documento->caminho_arquivo));
                if (!file_exists($dir)) {
                    mkdir($dir, 0755, true);
                }

                // Criar arquivo fictício no storage
                Storage::put($documento->caminho_arquivo, 'Conteúdo fictício do documento PDF - ' . $documento->nome_original);
            }
        }

        $this->command->info('Documentos de teste criados com sucesso!');
    }
}
