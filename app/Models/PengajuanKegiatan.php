<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengajuanKegiatan extends AppModel
{
    use HasFactory;

    protected $table = "pengajuan_kegiatans";

    protected $fillable = [
        'paket_kegiatan_id',
        'user_eksternal_id',
        'judul_pengajuan_kegiatan',
        'provinsi_kegiatan',
        'kabupaten_kegiatan',
        'kecamatan_kegiatan',
        'kelurahan_kegiatan',
        'alamat_kegiatan',
        'tanggal_kegiatan',
        'username',
    ];
}
