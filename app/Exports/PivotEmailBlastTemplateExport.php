<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PivotEmailBlastTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['email', 'nomor_pengajuan', 'status', 'catatan_log'];
    }

    public function array(): array
    {
        return [
            ['user@example.com', 'NP-001', 'diterima', 'Contoh catatan'],
            ['another@example.com', 'NP-002', 'ditolak', 'Opsional'],
            ['another_user@example.com', 'NP-003', 'tolak_profil', 'Opsional'],
        ];
    }
}
