<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AppModel;

class TolakPengajuanDanProfil extends AppModel
{
    use HasFactory;

    protected $table = "tolak_pengajuan_dan_profils";

    protected $fillable = [
        'nomor_pengajuan',
        'email_pic',
        'status_penolakan',
        'catatan_penlokan',
        'username'
    ];
}
