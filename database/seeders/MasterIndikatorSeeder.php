<?php

namespace Database\Seeders;

use App\Models\MasterIndikator;
use Illuminate\Database\Seeder;

class MasterIndikatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $masterIndikator = [
            [
                'nama_indikator'    => 'Laki-laki',
                'satuan'            => 'Orang',
                'tipe_data'         => 'number'
            ],
            [
                'nama_indikator'    => 'Perempuan',
                'satuan'            => 'Orang',
                'tipe_data'         => 'number'
            ],
            [
                'nama_indikator'    => 'Berat',
                'satuan'            => 'M2',
                'tipe_data'         => 'number'
            ],
            [
                'nama_indikator'    => 'Luas',
                'satuan'            => 'Hektar',
                'tipe_data'         => 'number'
            ],
        ];
        foreach ($masterIndikator as $item) {
            # code...
            MasterIndikator::updateOrCreate(['nama_indikator' => $item['nama_indikator']], $item);
        }
    }
}
