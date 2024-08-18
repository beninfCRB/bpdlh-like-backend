<?php

namespace App\Exports;

use App\Models\KelompokMasyarakat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataPicKelompokMasyarakatExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return KelompokMasyarakat::all();
    }

    public function headings(): array
    {
        return [
            'id',
            'jenis_kelompok_masyarakat_id',
            'kelompok_masyarakat',
            'Created At',
            'Updated At',
        ];
    }
}
