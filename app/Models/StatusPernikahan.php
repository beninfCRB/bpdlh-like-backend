<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StatusPernikahan extends AppModel
{
    use HasFactory;

    protected $table = 'status_pernikahans';

    protected $fillable = [
        'status_pernikahans',
        'flag',
        'username',
    ];
}
