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
        // $email = 'eoffice.bpdlh@gmail.com';
        // $verifikator = UserAkseslh::create([
        //     'email'         => $email,
        //     'password'      => Hash::make('password'),
        //     'status_user'   => 'ACTIVE',
        //     'flag'          => 1,
        //     'role_user'     => 'verifikator',
        // ]);

        // $email = 'eka.alifakih@bpdlh.id';
        // $validator = UserAkseslh::create([
        //     'email'         => $email,
        //     'password'      => Hash::make('password'),
        //     'status_user'   => 'ACTIVE',
        //     'flag'          => 1,
        //     'role_user'     => 'approver',
        // ]);

        $validator = UserAkseslh::create([
            'email'         => 'pmu@bpdlh.id',
            'nama_pic'      => 'PMU Bpdlh',
            'password'      => Hash::make('password'),
            'status_user'   => 'ACTIVE',
            'flag'          => 1,
            'role_user'     => 'approver',
        ]);
    }
}
