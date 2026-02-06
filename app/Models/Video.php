<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Video extends AppModel
{
    use HasFactory;

    protected $table = 'videos';

    protected $fillable = [
        'title',
        'description',
        'username',
    ];

    /**
     * Get the document_file associated with the Video
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function file()
    {
        return $this->morphOne(File::class, 'fileable')
            ->select(['id', 'group', 'visibility', 'file_name', 'file_path', 'fileable_id', 'real_name']);
    }
}
