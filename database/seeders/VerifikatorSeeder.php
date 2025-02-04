<?php

namespace Database\Seeders;

use App\Models\UserAkseslh;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class VerifikatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $userEmail = [
            [
                "name"  => "Dhonald Julian, S.Sos.",
                "email" => "julianjunot87@gmail.com"
            ],
            [
                "name"  => "Uus Danu Kusumah, S.Hut, M.E.",
                "email" => "uusdanukusumah@gmail.com"
            ],
            [
                "name"  => "Hans Nico A. Sinaga, S.Hut, M.P.",
                "email" => "nicosinaga.klhk@gmail.com"
            ],
            [
                "name"  => "R. Arif Sasongko Wijayanto, SH., MH.",
                "email" => 'arifsw78@gmail.com',
            ],
            [
                "name"  => "M. Shofwan Setiawan, S.Hut., M.Si",
                "email" => 'shofwansetiawan@gmail.com',
            ],
            [
                "name"  => "Tedi Bagus Prasetyo Mulyo, S.Hub. Int",
                "email" => 'tedibagus@menlhk.go.id',
            ],
            [
                "name"  => "Emi Mardiati, S.E.",
                "email" => 'kalpataru.klhk@gmail.com',
            ],
            [
                "name"  => "Agung Wicaksana, S.Hut.",
                "email" => 'wicaksanaagung02@gmail.com'
            ],
        ];

        foreach ($userEmail as $key => $value) {
            # code...
            UserAkseslh::create([
                'nama_pic'      => $value['name'],
                'email'         => $value['email'],
                'password'      => Hash::make('default_password@123'),
                'status_user'   => 'ACTIVE',
                'flag'          => 1,
                'role_user'     => 'verifikator',
            ]);
        }
    }
}
