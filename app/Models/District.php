<?php

namespace App\Models;

use App\Models\AppModel;
use App\Models\City;
use App\Models\Village;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends AppModel
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
    protected $table = 'indonesia_districts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'city_code', 'name', 'meta',
    ];
    public function kota()
    {
        return $this->belongsTo(City::class, 'city_code', 'code');
    }
    public function kelurahan()
    {
        return $this->hasMany(Village::class, 'district_code', 'code');
    }
}
