<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AkseslhPaketKegiatan extends AppModel
{
    use HasFactory;

    protected $table = "akseslh_paket_kegiatans";

    protected $fillable = [
        'akseslh_jenis_kegiatan_id',
        'nama_paket_kegiatan',
        'deskripsi_paket_kegiatan',
        'quota_paket_kegiatan',
        'pagu_paket_kegiatan',
        'tahap_pencairan_paket_kegiatan',
        'username'
    ];
}
