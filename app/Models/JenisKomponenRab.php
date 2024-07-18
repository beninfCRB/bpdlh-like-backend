<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisKomponenRab extends AppModel
{
    use HasFactory;

    protected $table = 'jenis_komponen_rabs';

    protected $fillable = [
        'jenis_komponen_rab',
        'flag',
        'username',
    ];
}


