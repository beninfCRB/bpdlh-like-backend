<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RabPengajuanPaketKegiatan extends AppModel
{
    use HasFactory;

    protected $table = "rab_pengajuan_paket_kegiatans";

    protected $fillable = [
        'pengajuan_kegiatan_id',
        'komponen_rab_id',
        'harga_unit',
        'qty',
        'flag',
        'username'
    ];
}
