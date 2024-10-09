<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogTahapanPengajuanKegiatan extends AppModel
{
    use HasFactory;

    protected $table = 'log_tahapan_pengajuan_kegiatans', $with = ['tahapan_pengajuan_kegiatan', 'user_akseslh_admin'];

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

    public function document_file()
    {
        return $this->morphOne(File::class, 'fileable')
            ->select(['id', 'group', 'visibility', 'file_name', 'file_path', 'fileable_id', 'real_name']);
    }
}
