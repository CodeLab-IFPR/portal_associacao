<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
            $email = $this->from('cdt.projetos@gmail.com', config('app.name'))
            ->to('cdt.projetos@gmail.com')
            ->view('emails.contact_message')
            ->with('data', $this->data)
            ->subject('Nova Mensagem de Contato');

            // Anexar arquivos, se houver
            if (isset($this->data['attachments'])) {
            $attachments = json_decode($this->data['attachments'], true);
            foreach ($attachments as $filePath) {
            $email->attach(public_path($filePath));
            }
        }
        return $email;
    }
}