<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTematikKegiatan extends AppModel
{
    use HasFactory;

    protected $table = 'sub_tematik_kegiatans';

    protected $fillable = [
        'tematik_kegiatan_id',
        'sub_tematik_kegiatan',
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
