<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogTahapanPengajuanKegiatan extends AppModel
{
    use HasFactory;

    protected $table = 'log_tahapan_pengajuan_kegiatans', $with = ['tahapan_pengajuan_kegiatan', 'user_akseslh'];

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
    public function user_akseslh(): BelongsTo
    {
        return $this->belongsTo(UserAkseslh::class, 'user_akseslh_id');
    }
}
