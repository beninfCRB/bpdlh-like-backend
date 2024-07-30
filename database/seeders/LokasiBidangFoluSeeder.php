<?php

namespace Database\Seeders;

use App\Models\LokasiBidangFolu;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class LokasiBidangFoluSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        LokasiBidangFolu::insert([
            [
                'id'                    => Uuid::uuid4()->toString(),
                'lokasi_bidang_folu'   => 'Hutan Produksi',
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
            ],
            [
                'id'                    => Uuid::uuid4()->toString(),
                'lokasi_bidang_folu'   =>
                'Taman Nasional',
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
            ],
            [
                'id'                    => Uuid::uuid4()->toString(),
                'lokasi_bidang_folu'   =>
                'Daerah Aliran Sungai',
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
            ],
            [
                'id'                    => Uuid::uuid4()->toString(),
                'lokasi_bidang_folu'   =>
                'Lahan Gambut',
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
            ],
        ]);
    }
}
