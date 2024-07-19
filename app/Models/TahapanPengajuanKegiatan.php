<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TahapanPengajuanKegiatan extends AppModel
{
    use HasFactory;

    protected $table = 'tahapan_pengajuan_kegiatans';

    protected $fillable = [
        'deskripsi_kegiatan',
        'flag',
        'username',
    ];
}
