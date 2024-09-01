<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RabPengajuanPaketKegiatan extends AppModel
{
    use HasFactory;

    protected $table = "rab_pengajuan_paket_kegiatans";

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
}
