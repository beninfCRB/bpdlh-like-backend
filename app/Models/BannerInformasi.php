<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BannerInformasi extends AppModel
{
    use HasFactory;

    protected $table = "banner_informasis";

    protected $fillable = [
        'deskripsi',
        'flag',
        'username'
    ];
}
