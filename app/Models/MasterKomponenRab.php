<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterKomponenRab extends AppModel
{
    use HasFactory;

    protected $table = 'master_komponen_rabs';

    protected $fillable = [
        'jenis_komponen_rab_id',
        'satuan_id',
        'komponen_rab',
        'standar_harga_unit',
        'flag',
        'username',
    ];

    /**
     * Get the jenis_kegiatan that owns the AkseslhPaketKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jenis_komponen(): BelongsTo
    {
        return $this->belongsTo(JenisKomponenRab::class, 'jenis_komponen_rab_id');
    }

    /**
     * Get the tematik_kegiatan that owns the AkseslhPaketKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function satuan(): BelongsTo
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
}
