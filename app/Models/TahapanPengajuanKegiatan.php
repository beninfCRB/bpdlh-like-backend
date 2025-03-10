<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TahapanPengajuanKegiatan extends AppModel
{
    use HasFactory;

    protected $table = 'tahapan_pengajuan_kegiatans';

    protected $fillable = [
        'deskripsi_kegiatan',
        'sort',
        'code_id',
        'flag',
        'username',
    ];

    /**
     * Get all of the jenis_dokumen for the TahapanPengajuanKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jenis_dokumen(): HasMany
    {
        return $this->hasMany(JenisDokumen::class, 'tahapan_pengajuan_kegiatan_id');
    }

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'sort' => 'integer',
    ];
}
