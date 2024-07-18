<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAkseslh extends AppModel
{
    use HasFactory;

    protected $table = "user_akseslhs";

    protected $fillable = [
        'data_pic_kelompok_masyarakat_id',
        'email',
        'password',
        'status_user',
        'flag',
        'username',
    ];

    /**
     * Get the kelompok_masyarakat that owns the AkseslhUserEksternal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function data_pic_kelompok_masyarakat(): BelongsTo
    {
        return $this->belongsTo(DataPicKelompokMasyarakat::class, 'data_pic_kelompok_masyarakat_id');
    }
}
