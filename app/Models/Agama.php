<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agama extends AppModel
{
    use HasFactory;

    protected $table = 'agamas';

    protected $fillable = [
        'agama',
        'flag',
        'username',
    ];
}
