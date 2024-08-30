<?php

namespace App\Exports;

use App\Models\UserAkseslh;
use App\Services\Akseslh\PengajuanKegiatanService;
use Maatwebsite\Excel\Concerns\FromCollection;

class PengajuanKegiatanExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        //
        return UserAkseslh::all();
    }
}
