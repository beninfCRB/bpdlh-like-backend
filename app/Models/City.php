<?php

namespace App\Models;

use App\Models\AppModel;
use App\Models\Province;
use App\Models\District;
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
    // Definisikan relasi belongsTo ke model Provinsi
    public function provinsi()
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }
    public function kecamatan()
    {
        return $this->hasMany(District::class, 'city_code', 'code');
    }
}
