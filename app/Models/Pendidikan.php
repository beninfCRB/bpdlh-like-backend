<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pendidikan extends AppModel
{
    use HasFactory;

    protected $table = 'pendidikans';

    protected $fillable = [
        'pendidikan',
        'flag',
    ];
}
