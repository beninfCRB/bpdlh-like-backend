<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LokasiBidangFolu extends AppModel
{
    use HasFactory;

    protected $table = 'lokasi_bidang_folus';

    protected $fillable = [
        'lokasi_bidang_folu',
        'flag',
        'username',
    ];
}
