<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogTahapanPengajuanKegiatan extends AppModel
{
    use HasFactory;

    protected $table = 'log_tahapan_pengajuan_kegiatans';

    protected $fillable = [
        'pengajuan_kegiatan_id',
        'tahapan_pengajuan_kegiatan_id',
        'tanggal_masuk',
        'tanggal_selesai',
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
}
