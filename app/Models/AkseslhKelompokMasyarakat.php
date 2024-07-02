<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AkseslhKelompokMasyarakat extends AppModel
{
    use HasFactory;

    protected $table = "akseslh_kelompok_masyarakats";
    protected $fillable = [
        'akseslh_jenis_kelompok_masyarakat_id',
        'kelompok_masyarakat',
        'flag',
        'username'
    ];
}
