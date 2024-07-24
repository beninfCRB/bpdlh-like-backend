<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengajuanKegiatan extends AppModel
{
    use HasFactory;

    protected $table = "pengajuan_kegiatans";

    protected $fillable = [
        'paket_kegiatan_id',
        'user_akseslh_id',
        'judul_pengajuan_kegiatan',
        'provinsi_kegiatan',
        'kabupaten_kegiatan',
        'kecamatan_kegiatan',
        'kelurahan_kegiatan',
        'alamat_kegiatan',
        'tanggal_mulai_kegiatan',
        'tanggal_akhir_kegiatan',
        'time_mulai_kegiatan',
        'time_akhir_kegiatan',
        'proposal_kegiatan',
        'tujuan_kegiatan',
        'ruang_lingkup_kegiatan',
        'username',
    ];

    /**
     * Get the jenis_kegiatan that owns the AkseslhPaketKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paket_kegiatan(): BelongsTo
    {
        return $this->belongsTo(PaketKegiatan::class, 'akseslh_paket_kegiatan_id');
    }

    /**
     * Get the tematik_kegiatan that owns the AkseslhPaketKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user_eksternal(): BelongsTo
    {
        return $this->belongsTo(UserEksternal::class, 'akseslh_user_eksternal_id');
    }
}
