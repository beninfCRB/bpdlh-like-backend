<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\UserAkseslh;
use Illuminate\Database\Seeder;
use App\Services\EmailPhpService;
use Illuminate\Support\Facades\Hash;

class UserAkseslhSeeder extends Seeder
{
    protected $emailService;

    public function __construct(EmailPhpService $emailService)
    {
        $this->emailService = $emailService;
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // Make user verifikator
        $email = 'eoffice.bpdlh@gmail.com';
        $default_password =
            crypt($email . Carbon::now()->format('d M Y H:i:s'), $email);
        $verifikator = UserAkseslh::create([
            'email'         => $email,
            'password'      => Hash::make($default_password),
            'status_user'   => 'ACTIVE',
            'flag'          => 1,
            'role_user'     => 'verifikator',
        ]);

        // Mengirim email menggunakan PHPMailer
        $this->emailService->sendEmail($email, 'Register Notification', $verifikator, $default_password, null, 'mail.seeder-register-mail');

        $email = 'eka.alifakih@bpdlh.id';
        $default_password =
            crypt($email . Carbon::now()->format('d M Y H:i:s'), $email);
        $validator = UserAkseslh::create([
            'email'         => $email,
            'password'      => Hash::make($default_password),
            'status_user'   => 'ACTIVE',
            'flag'          => 1,
            'role_user'     => 'approver',
        ]);

        // Mengirim email menggunakan PHPMailer
        $this->emailService->sendEmail($email, 'Register Notification', $validator, $default_password, null, 'mail.seeder-register-mail');
    }
}
