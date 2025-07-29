<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TolakPengajuanDanProfilTemplate implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['nomor_pengajuan', 'email_pic', 'status_penolakan', 'catatan_penolakan'];
    }

    public function array(): array
    {
        return [
            ['01211-2507-00027', 'test@email.test', 'pengajuan', 'Contoh catatan'],
            ['01211-2507-00026', 'test@email.test', 'pengajuan', 'Opsional'],
            ['01211-2507-00025', 'test@email.test', 'profil', 'Opsional'],
            ['01211-2507-00024', 'test@email.test', 'profil', 'Opsional'],
        ];
    }
}
