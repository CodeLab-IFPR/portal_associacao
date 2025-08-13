<?php

namespace App\Jobs;

use App\Mail\SendPasswordMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\MaxAttemptsExceededException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPasswordJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $password;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::to($this->user->email)->send(new SendPasswordMail($this->password));
        } catch (\Exception $e) {
            // Se falhar, tente novamente
            if ($this->attempts() < 5) {
                $this->release(60); // Tentar novamente após 60 segundos
            } else {
                throw new MaxAttemptsExceededException('Falha ao enviar email após várias tentativas.');
            }
        }
    }
}
