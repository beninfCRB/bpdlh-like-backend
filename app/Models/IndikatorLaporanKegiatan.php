<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IndikatorLaporanKegiatan extends AppModel
{
    use HasFactory;

    protected $table = 'indikator_laporan_kegiatans';

    protected $fillable = [
        'master_data_indikator_laporan_id',
        'pengajuan_kegiatan_id',
        'nilai_laporan',
        'flag',
        'username',
    ];

    /**
     * Get the master_data_indikator_laporan that owns the IndikatorLaporanKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function master_data_indikator_laporan(): BelongsTo
    {
        return $this->belongsTo(MasterDataIndikatorLaporan::class, 'master_data_indikator_laporan_id');
    }

    /**
     * Get the pengajuan_kegiatan that owns the IndikatorLaporanKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pengajuan_kegiatan(): BelongsTo
    {
        return $this->belongsTo(PengajuanKegiatan::class, 'pengajuan_kegiatan_id');
    }
}
