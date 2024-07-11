<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
