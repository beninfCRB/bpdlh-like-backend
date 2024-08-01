<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailLogTahapanPengajuanKegiatan extends AppModel
{
    use HasFactory;

    protected $table = 'detail_log_tahapan_pengajuan_kegiatans';

    protected $fillable = [
        'pengajuan_kegiatan_id',
        'tahapan_pengajuan_kegiatan_id',
        'tanggal_masuk',
        'tanggal_selesai',
        'flag',
        'username',
    ];
}
