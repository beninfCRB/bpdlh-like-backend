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
                'nama_indikator'    => 'Laki - laki',
                'satuan'            => 'Orang',
                'tipe_data'         => 'number'
            ],
            [
                'nama_indikator'    => 'Perempuan',
                'satuan'            => 'Orang',
                'tipe_data'         => 'number'
            ],
            [
                'nama_indikator'    => 'Sampah',
                'satuan'            => 'M2',
                'tipe_data'         => 'number'
            ],
            [
                'nama_indikator'    => 'Sampah',
                'satuan'            => 'Kg',
                'tipe_data'         => 'number'
            ],
            [
                'nama_indikator'    => 'Pohon',
                'satuan'            => 'Hektar',
                'tipe_data'         => 'number'
            ],
            [
                'nama_indikator'    => 'Kwh',
                'satuan'            => 'Kwh',
                'tipe_data'         => 'number'
            ],
        ];
        foreach ($masterIndikator as $item) {
            # code...
            MasterIndikator::create($item);
        }
    }
}
