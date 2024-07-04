<?php

namespace Database\Seeders;

use App\Models\AkseslhJenisKelompokMasyarakat;
use App\Models\AkseslhKelompokMasyarakat;
use App\Models\AkseslhUserEksternal;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(10)->create();
        $jenis = AkseslhJenisKelompokMasyarakat::create([
            'jenis_kelompok_masyarakat' => 'Perorangan dalam kelompok',
            'short_id'  => 1,
            'flag'      => 1,
        ]);

        $kelompok = AkseslhKelompokMasyarakat::create([
            'akseslh_jenis_kelompok_masyarakat_id' => $jenis->id,
            'kelompok_masyarakat'                   => 'KM Dummy Coba',
            'flag'  => 1,
        ]);

        $user = AkseslhUserEksternal::create([
            'akseslh_kelompok_masyarakat_id'    => $kelompok->id,
            'email_user_eksternal'              => 'rifqiarnoldy@gmail.com',
            'nama_user_eksternal'               => 'Rifqi Arnoldy',
            'jenis_identitas_user_eksternal'    => 'KTP',
            'nomor_identitas_user_eksternal'    => '3210073007980001',
            'nomor_hp_user_eksternal'           => '08996341271',
        ]);
    }
}
