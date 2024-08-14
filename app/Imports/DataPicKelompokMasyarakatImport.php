<?php

namespace App\Imports;

use App\Models\DataPicKelompokMasyarakat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataPicKelompokMasyarakatImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new DataPicKelompokMasyarakat([
            'name'      => $row['name'],
            'email'     => $row['email'],
            'password'  => bcrypt($row['password']), // Misalnya, jika Anda ingin mengenkripsi password
        ]);
    }
}
