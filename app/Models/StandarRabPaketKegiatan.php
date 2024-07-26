<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandarRabPaketKegiatan extends AppModel
{
    use HasFactory;

    protected $table = 'standar_rab_paket_kegiatans';

    protected $fillable = [
        'paket_kegiatan_id',
        'master_komponen_rab_id',
        'standar_qty',
        'standar_harga_unit',
        'flag',
        'username',
    ];
}
