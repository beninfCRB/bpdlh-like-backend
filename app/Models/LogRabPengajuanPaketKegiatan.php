<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogRabPengajuanPaketKegiatan extends AppModel
{
    use HasFactory;

    protected $table = "log_rab_pengajuan_paket_kegiatans";

    protected $fillable = [
        'pengajuan_kegiatan_id',
        'komponen_rab_id',
        'harga_unit',
        'qty',
        'flag',
        'username'
    ];

    /**
     * Get the master_komponen_rab that owns the RabPengajuanPaketKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function master_komponen_rab(): BelongsTo
    {
        return $this->belongsTo(MasterKomponenRab::class, 'komponen_rab_id');
    }

    /**
     * Get the pengajuan_kegiatan that owns the RabPengajuanPaketKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pengajuan_kegiatan(): BelongsTo
    {
        return $this->belongsTo(PengajuanKegiatan::class, 'pengajuan_kegiatan_id');
    }
}
