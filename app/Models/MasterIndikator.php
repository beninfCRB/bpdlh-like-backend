<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AppModel;

class MasterIndikator extends AppModel
{
    use HasFactory;

    protected $table = "master_indikators";

    protected $fillable = [
        'nama_indikator',
        'satuan',
        'tipe_data',
        'sort',
        'flag',
        'username',
    ];
}
