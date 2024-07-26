<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahapSalurPaketKegiatan extends AppModel
{
    use HasFactory;

    protected $table = 'tahap_salur_paket_kegiatans';

    protected $fillable = [
        'paket_kegiatan_id',
        'tahap_salur',
        'porsi_pencairan',
        'flag',
        'username',
    ];
}
