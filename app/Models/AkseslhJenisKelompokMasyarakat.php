<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AkseslhJenisKelompokMasyarakat extends AppModel
{
    use HasFactory;

    protected $table = "akseslh_jenis_kelompok_masyarakats";

    protected $fillable = [
        'jenis_kelompok_masyarakat',
        'short_id',
        'flag',
        'username'
    ];
}
