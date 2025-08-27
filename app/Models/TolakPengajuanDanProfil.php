<?php

namespace App\Models;

use App\Models\AppModel;
use App\Models\UserAkseslh;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TolakPengajuanDanProfil extends AppModel
{
    use HasFactory;

    protected $table = "tolak_pengajuan_dan_profils";

    protected $fillable = [
        'nomor_pengajuan',
        'email_pic',
        'status_penolakan',
        'catatan_penolakan',
        'status',
        'username'
    ];

    /**
     * Get the pic_kelompok_masyarakat that owns the TolakPengajuanDanProfil
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pic_kelompok_masyarakat(): BelongsTo
    {
        return $this->belongsTo(DataPicKelompokMasyarakat::class, 'email_pic', 'email_pic');
    }

    /**
     * Get the user_akseslh that owns the TolakPengajuanDanProfil
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user_akseslh(): BelongsTo
    {
        return $this->belongsTo(UserAkseslh::class, 'username', 'id');
    }

    /**
     * Get the pengajuan_kegiatan that owns the TolakPengajuanDanProfil
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pengajuan_kegiatan(): BelongsTo
    {
        return $this->belongsTo(PengajuanKegiatan::class, 'nomor_pengajuan', 'nomor_pengajuan');
    }
}
