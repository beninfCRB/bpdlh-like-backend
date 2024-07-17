<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends AppModel
{
    use HasFactory;

    protected $table = 'satuans';

    protected $fillable = [
        'satuan',
        'flag',
        'username',
    ];
}
