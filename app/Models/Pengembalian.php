<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengembalian extends AppModel
{
    use HasFactory;

    protected $table = "pengembalians";

    protected $fillable = [
        'pengajuan_kegiatan_id',
        'jumlah_pengembalian',
        'flag',
    ];

    /**
     * Get the pengajuan_kegiatan that owns the Pengembalian
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pengajuan_kegiatan(): BelongsTo
    {
        return $this->belongsTo(PengajuanKegiatan::class, 'pengajuan_kegiatan_id');
    }

    public function document()
    {
        return $this->morphOne(File::class, 'fileable')
            ->select(['id', 'group', 'visibility', 'file_name', 'file_path', 'fileable_id']);
    }
}
