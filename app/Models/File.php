<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends AppModel
{
    use HasFactory;

    public function fileable()
    {
        return $this->morphTo();
    }

    protected $table = 'files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group',
        'fileable_type',
        'fileable_id',
        'visibility',
        'real_name',
        'extension',
        'size',
        'mime_type',
        'file_dir',
        'file_name',
        'file_path',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * Get the pengajuan_kegiatan that owns the File
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pengajuan_kegiatan(): BelongsTo
    {
        return $this->belongsTo(PengajuanKegiatan::class, 'fileable_id');
    }

    /**
     * Get the data_pic_kelompok_masyarakat that owns the File
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pic_kelompok(): BelongsTo
    {
        return $this->belongsTo(DataPicKelompokMasyarakat::class, 'fileable_id');
    }
}
