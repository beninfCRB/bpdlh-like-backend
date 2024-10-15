<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterDataIndikatorLaporan extends AppModel
{
    use HasFactory;

    protected $table = 'master_data_indikator_laporans';

    protected $fillable = [
        'jenis_kegiatan_id',
        'sub_tematik_kegiatan_id',
        'nama_indikator',
        'satuan',
        'tipe_data',
        'flag',
        'username',
    ];

    /**
     * Get the jenis_kegiatan that owns the MasterDataIndikatorLaporan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jenis_kegiatan(): BelongsTo
    {
        return $this->belongsTo(JenisKegiatan::class, 'jenis_kegiatan_id');
    }

    /**
     * Get the sub_tematik_kegiatan that owns the MasterDataIndikatorLaporan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sub_tematik_kegiatan(): BelongsTo
    {
        return $this->belongsTo(SubTematikKegiatan::class, 'sub_tematik_kegiatan_id');
    }
}
