<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends AppModel
{
    use HasFactory;

    /**
     * The database connection that should be used by the model.
     *
     * @var string
     */
    protected $connection = 'mysql_wilayah';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'indonesia_cities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'province_code', 'name', 'meta',
    ];
}
