<?php

namespace Database\Seeders;

use App\Models\PivotEmailBlast;
use Illuminate\Database\Seeder;
use App\Models\PengajuanKegiatan;

class PivotEmailBlastSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $pengajuans = PengajuanKegiatan::with('user_akseslh')->take(10)->get(); // ambil 200 dulu untuk testing

        $statuses = ['diterima', 'ditolak', 'tolak_profil'];

        foreach ($pengajuans as $pengajuan) {
            PivotEmailBlast::create([
                'nomor_pengajuan' => $pengajuan->nomor_pengajuan,
                'email' => $pengajuan->user_akseslh->email ?? 'test@email.test',
                'status' => $statuses[array_rand($statuses)], // random pilih dari tiga status
                'catatan_log' => null, // default null
            ]);
        }
    }
}
