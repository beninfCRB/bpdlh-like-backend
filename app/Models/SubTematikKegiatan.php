<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubTematikKegiatan extends AppModel
{
    use HasFactory;

    protected $table = 'sub_tematik_kegiatans';

    protected $fillable = [
        'sub_tematik_kegiatan',
        'deskripsi_tematik',
        'short_id',
        'flag',
        'username',
    ];

    public function image()
    {
        return $this->morphOne(File::class, 'fileable')
            ->select(['id', 'group', 'visibility', 'file_name', 'file_path', 'fileable_id']);
    }
}
