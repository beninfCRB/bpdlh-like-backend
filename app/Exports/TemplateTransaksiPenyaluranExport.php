<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TemplateTransaksiPenyaluranExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['No', 'Nomor Pengajuan', 'Bank', 'No. Rek Giro', 'Nama Pemilik Rekening', 'Tanggal Pencairan', 'Nominal Termin 1'];
    }

    public function array(): array
    {
        return [
            [1, '1234567890', 'Mandiri', '1234567890', 'John Doe', '2023-01-01', 1000000],
            [2, '0987654321', 'BRI', '0987654321', 'Jane Doe', '2023-02-01', 2000000],
            // Tambahkan data dummy lainnya sesuai kebutuhan
        ];
    }
}
