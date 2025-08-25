<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KelompokMasyarakat extends AppModel
{
    use HasFactory;

    protected $table = "kelompok_masyarakats";

    protected $fillable = [
        'jenis_kelompok_masyarakat_id',
        'kelompok_masyarakat',
        'provinsi_kelompok_masyarakat_id',
        'kabupaten_kelompok_masyarakat_id',
        'kecamatan_kelompok_masyarakat_id',
        'kelurahan_kelompok_masyarakat_id',
        'flag',
        'username'
    ];

    /**
     * Get the jenis that owns the AkseslhKelompokMasyarakat
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jenis(): BelongsTo
    {
        return $this->belongsTo(JenisKelompokMasyarakat::class, 'jenis_kelompok_masyarakat_id')->withTrashed();
    }
}
