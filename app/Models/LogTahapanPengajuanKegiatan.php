<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogTahapanPengajuanKegiatan extends AppModel
{
    use HasFactory;

    protected $table = 'log_tahapan_pengajuan_kegiatans';

    protected $fillable = [
        'pengajuan_kegiatan_id',
        'tahapan_pengajuan_kegiatan_id',
        'tanggal_masuk',
        'tanggal_selesai',
        'flag',
        'username',
    ];
}
