<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Province extends AppModel
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
    protected $table = 'indonesia_provinces';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name', 'meta',
    ];
}
