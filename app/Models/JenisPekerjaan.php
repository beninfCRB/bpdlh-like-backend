<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisPekerjaan extends AppModel
{
    use HasFactory;

    protected $table = 'jenis_pekerjaans';

    protected $fillable = [
        'jenis_pekerjaan',
        'flag',
        'username',
    ];
}
