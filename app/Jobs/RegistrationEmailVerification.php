<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Services\EmailPhpService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class RegistrationEmailVerification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email, $token;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $token)
    {
        //
        $this->email = $email;
        $this->token = $token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(EmailPhpService $emailPhpService)
    {
        //
        $emailPhpService->getTokenAktivasi($this->email, 'Token Verifikasi Email', $this->token);
    }
}
