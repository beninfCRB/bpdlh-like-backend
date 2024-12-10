<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailLogTahapanPengajuanKegiatan extends AppModel
{
    use HasFactory;

    protected $table = 'detail_log_tahapan_pengajuan_kegiatans';

    protected $fillable = [
        'pengajuan_kegiatan_id',
        'tahapan_pengajuan_kegiatan_id',
        'tanggal_masuk',
        'tanggal_selesai',
        'user_akseslh_id',
        'flag',
        'username',
    ];

    /**
     * Get the jenis_kegiatan that owns the AkseslhPaketKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tahapan_pengajuan_kegiatan(): BelongsTo
    {
        return $this->belongsTo(TahapanPengajuanKegiatan::class, 'tahapan_pengajuan_kegiatan_id');
    }

    /**
     * Get the user_akselh that owns the LogTahapanPengajuanKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user_akseslh_admin(): BelongsTo
    {
        return $this->belongsTo(UserAkseslh::class, 'user_akseslh_id');
    }

    /**
     * Get the pengajuan_kegiatan that owns the LogTahapanPengajuanKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pengajuan_kegiatan(): BelongsTo
    {
        return $this->belongsTo(PengajuanKegiatan::class, 'pengajuan_kegiatan_id');
    }
}
