<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AppModel;

class MasterDataBank extends AppModel
{
    use HasFactory;

    protected $table = "master_data_banks";

    protected $fillabe = [
        'nama_bank',
        'bank_code',
        'flag',
        'username',
    ];
}
