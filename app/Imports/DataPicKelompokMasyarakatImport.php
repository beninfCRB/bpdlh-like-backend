<?php

namespace App\Imports;

use App\Models\DataPicKelompokMasyarakat;
use App\Models\UserAkseslh;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataPicKelompokMasyarakatImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            # code...
            $dataPic = DataPicKelompokMasyarakat::create([
                'kelompok_masyarakat_id'    => $row['kelompok_masyarakat_id'],
                'nama_pic'                  => $row['nama_pic'],
                'jenis_identitas_pic'       => $row['jenis_identitas_pic'],
                'nomor_identitas_pic'       => $row['nomor_identitas_pic'],
                'email_pic'                 => $row['email_pic'],
                'nohp_pic'                  => $row['nohp_pic'],
                'alamat_pic'                => $row['alamat_pic'],
                'kelurahan_pic'             => 1,
                'kecamatan_pic'             => 1,
                'kabupaten_pic'             => 1,
                'provinsi_pic'              => 1,
                'flag'                      => 1,
            ]);

            $userAkseslh = UserAkseslh::create([
                'data_pic_kelompok_masyarakat_id'   => $dataPic->id,
                'nama_pic'                          => $row['nama_pic'],
                'email'                             => $row['email_pic'],
                'status_user'                       => 'NON ACTIVE',
                'role_user'                         => 'maker',
                'flag'                              => 1,
            ]);
        }
    }

    public function model(array $row)
    {
        dd($row);
        return new DataPicKelompokMasyarakat([
            'kelompok_masyarakat_id'    => $row['kelompok_masyarakat_id'],
            'nama_pic'                  => $row['nama_pic'],
            'jenis_identitas_pic'       => $row['jenis_identitas_pic'],
            'nomor_identitas_pic'       => $row['nomor_identitas_pic'],
            'email_pic'                 => $row['email_pic'],
            'nohp_pic'                  => $row['nohp_pic'],
            'alamat_pic'                => $row['alamat_pic'],
            'kelurahan_pic'             => 1,
            'kecamatan_pic'             => 1,
            'kabupaten_pic'             => 1,
            'provinsi_pic'              => 1,
            'flag'                      => 1,
            'password'                  => bcrypt($row['password']), // Misalnya, jika Anda ingin mengenkripsi password
        ]);
    }
}
