<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogJadwalPembukaan extends AppModel
{
    use HasFactory;

    protected $table = 'log_jadwal_pembukaans';

    protected $fillable = [
        'tanggal_awal',
        'tanggal_akhir',
        'jam_awal',
        'jam_akhir',
        'flag',
        'username',
    ];
}
