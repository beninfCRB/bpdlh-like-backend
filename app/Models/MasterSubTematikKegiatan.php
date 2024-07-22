<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterSubTematikKegiatan extends AppModel
{
    use HasFactory;

    protected $table = 'master_sub_tematik_kegiatans';

    protected $fillable = [
        'tematik_kegiatan_id',
        'sub_tematik_kegiatan_id',
        'short_id',
        'deskripsi_tematik',
        'flag',
        'username',
    ];

    /**
     * Get the tematik_kegiatan that owns the MasterSubTematikKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tematik_kegiatan(): BelongsTo
    {
        return $this->belongsTo(TematikKegiatan::class, 'tematik_kegiatan_id');
    }

    /**
     * Get the sub_tematik_kegiatan that owns the MasterSubTematikKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sub_tematik_kegiatan(): BelongsTo
    {
        return $this->belongsTo(SubTematikKegiatan::class, 'sub_tematik_kegiatan_id');
    }
}
