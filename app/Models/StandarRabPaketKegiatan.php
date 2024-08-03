<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StandarRabPaketKegiatan extends AppModel
{
    use HasFactory;

    protected $table = 'standar_rab_paket_kegiatans';

    protected $fillable = [
        'paket_kegiatan_id',
        'master_komponen_rab_id',
        'standar_qty',
        'standar_harga_unit',
        'flag',
        'username',
    ];

    /**
     * Get the master_komponen_rab that owns the StandarRabPaketKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function master_komponen_rab(): BelongsTo
    {
        return $this->belongsTo(MasterKomponenRab::class, 'master_komponen_rab_id');
    }
}
