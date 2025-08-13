<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webklex\IMAP\Facades\Client;
use App\Models\Contact;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class CheckEmailMessages extends Command
{
    protected $signature = 'email:check';
    protected $description = 'Check email messages and save them to the database';

    public function handle()
    {
        try {
            $client = Client::account('gmail'); // Use o identificador da conta configurada
            $client->connect();
    
            Log::info('Conectado ao servidor IMAP com sucesso.');
    
            $folder = $client->getFolder('INBOX');
            $messages = $folder->messages()->unseen()->get();
    
            Log::info('Número de mensagens não lidas: ' . $messages->count());
    
            foreach ($messages as $message) {
                $fromEmail = $message->getFrom()[0]->mail;
                
                if ($fromEmail === 'cdt.projetos@gmail.com') {
                    continue;
                }
    
                // Processar anexos
                $attachments = [];
                foreach ($message->getAttachments() as $attachment) {
                    $fileName = time() . '_';
                    $destinationPath = public_path('anexos_contacts');
                    if (!File::exists($destinationPath)) {
                        File::makeDirectory($destinationPath, 0755, true);
                    }
                    $attachment->save($destinationPath.'/'.$fileName);
                    $attachments[] = 'anexos_contacts/' . $fileName.$attachment->getName();
                }

    
                Contact::create([
                    'name' => imap_utf8($message->getFrom()[0]->personal),
                    'email' => $fromEmail,
                    'message' => $message->getTextBody(),
                    'read' => false,
                    'attachments' => json_encode($attachments),
                    'subject' => imap_utf8($message->getSubject()),
                ]);
    
                // Marcar a mensagem como lida
                $message->setFlag('Seen');
    
                Log::info('Mensagem de ' . $fromEmail . ' salva no banco de dados.');
            }
    
            $this->info('Email messages checked and saved to the database.');
        } catch (\Exception $e) {
            Log::error('Erro ao conectar ou ler e-mails: ' . $e->getMessage());
            $this->error('Erro ao conectar ou ler e-mails: ' . $e->getMessage());
        }
    
        return 0;
    }
}