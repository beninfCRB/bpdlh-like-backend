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
        $pengajuans = PengajuanKegiatan::with('user_akseslh')->take(50)->get(); // ambil 200 dulu untuk testing

        foreach ($pengajuans as $pengajuan) {
            PivotEmailBlast::create([
                'pengajuan_kegiatan_id' => $pengajuan->id,
                'email' => $pengajuan->user_akseslh->email ?? 'test@email.test', // bisa ganti sesuai field user kalau ada
                'status' => rand(0, 1) ? 'diterima' : 'ditolak',
            ]);
        }
    }
}
