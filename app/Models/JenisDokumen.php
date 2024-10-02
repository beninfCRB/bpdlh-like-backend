<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisDokumen extends AppModel
{
    use HasFactory;

    protected $table = 'jenis_dokumens';

    protected $fillable = [
        'jenis_dokumen',
        'tahapan_pengajuan_kegiatan_id',
        'flag',
        'username',
    ];

    /**
     * Get all of the tahapan_pengajuan_kegiatan for the JenisKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tahapan_pengajuan_kegiatan(): BelongsTo
    {
        return $this->belongsTo(TahapanPengajuanKegiatan::class, 'tahapan_pengajuan_kegiatan_id');
    }
    public function document_file()
    {
        return $this->morphOne(File::class, 'fileable')
            ->select(['id', 'group', 'visibility', 'file_name', 'file_path', 'fileable_id']);
    }
}
