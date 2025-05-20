<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TemplateTransaksiPenyaluranExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['nomor_pengajuan', 'nama_bank', 'nomor_rekening', 'nama_pemilik_rekening', 'tanggal_penyaluran', 'nilai_penyaluran'];
    }

    public function array(): array
    {
        return [
            ['1234567890', 'Bank Mandiri', '1234567890', 'John Doe', '2023-01-01', 1000000],
            ['0987654321', 'Bank BRI', '0987654321', 'Jane Doe', '2023-02-01', 2000000],
            // Tambahkan data dummy lainnya sesuai kebutuhan
        ];
    }
}
