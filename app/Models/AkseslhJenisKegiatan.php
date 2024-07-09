<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AppModel;

class AkseslhJenisKegiatan extends AppModel
{
    use HasFactory;

    protected $table = 'jenis_kegiatans';

    protected $fillable = [
        'jenis_kegiatan',
        'username',
    ];
}
