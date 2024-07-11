<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TematikKegiatan extends AppModel
{
    use HasFactory;

    protected $table = 'tematik_kegiatans';

    protected $fillable = [
        'tematik_kegiatan',
        'short_id',
        'icon_tematik',
        'username',
    ];

    public function image()
    {
        return $this->morphOne(File::class, 'fileable')
            ->select(['id', 'group', 'visibility', 'file_name', 'file_path', 'fileable_id']);
    }
}
