<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisKegiatan extends AppModel
{
    use HasFactory;

    protected $table = 'jenis_kegiatans';

    protected $fillable = [
        'jenis_kegiatan',
        'short_id',
        'code_id',
        'flag',
        'username',
    ];

    /**
     * Get all of the paket_kegiatan for the JenisKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paket_kegiatan(): HasMany
    {
        return $this->hasMany(PaketKegiatan::class, 'jenis_kegiatan_id');
    }
}
