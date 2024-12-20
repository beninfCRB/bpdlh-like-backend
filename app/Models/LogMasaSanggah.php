<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogMasaSanggah extends AppModel
{
    use HasFactory;

    protected $table = "log_masa_sanggahs";

    protected $fillable = [
        'tanggal_awal',
        'tanggal_akhir',
        'jam_awal',
        'jam_akhir',
        'batas_pengajuan',
        'flag',
        'username'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'batas_pengajuan' => 'string',
    ];
}
