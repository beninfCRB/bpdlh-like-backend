<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CatatanLogTahapanPengajuanKegiatan extends AppModel
{
    use HasFactory;

    protected $table = 'catatan_log_tahapan_pengajuan_kegiatans';

    protected $fillable = [
        'log_tahapan_pengajuan_kegiatan_id',
        'catatan_log',
        'flag',
        'username',
    ];

    /**
     * Get the log_tahapan_pengajuan_kegiatan that owns the CatatanLogTahapanPengajuanKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function log_tahapan_pengajuan_kegiatan(): BelongsTo
    {
        return $this->belongsTo(LogTahapanPengajuanKegiatan::class, 'log_tahapan_pengajuan_kegiatan_id');
    }
}
