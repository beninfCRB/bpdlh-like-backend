<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Services\EmailPhpService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class RegistrationPasswordDefault implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email, $user, $default_password;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $user, $default_password)
    {
        //
        $this->email = $email;
        $this->user = $user;
        $this->default_password = $default_password;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(EmailPhpService $emailPhpService)
    {
        //
        $emailPhpService->sendEmail($this->email, 'Register Notification', $this->user, $this->default_password);
    }
}
