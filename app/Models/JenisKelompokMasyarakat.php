<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisKelompokMasyarakat extends AppModel
{
    use HasFactory;

    protected $table = "jenis_kelompok_masyarakats";

    protected $fillable = [
        'jenis_kelompok_masyarakat',
        'short_id',
        'code_id',
        'flag',
        'username'
    ];
}
