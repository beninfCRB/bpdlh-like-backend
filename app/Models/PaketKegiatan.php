<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaketKegiatan extends AppModel
{
    use HasFactory;

    protected $table = "paket_kegiatans";

    protected $fillable = [
        'jenis_kegiatan_id',
        'nama_paket_kegiatan',
        'deskripsi_paket_kegiatan',
        'quota_paket_kegiatan',
        'pagu_paket_kegiatan',
        'tahap_pencairan_paket_kegiatan',
        'username'
    ];

    /**
     * Get the jenis_kegiatan that owns the AkseslhPaketKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jenis_kegiatan(): BelongsTo
    {
        return $this->belongsTo(JenisKegiatan::class, 'jenis_kegiatan_id');
    }
}
