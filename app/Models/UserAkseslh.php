<?php

namespace App\Models;

use App\Models\AppAuthenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class UserAkseslh extends AppAuthenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'akseslh';

    protected $table = "user_akseslhs";

    protected $fillable = [
        'data_pic_kelompok_masyarakat_id',
        'nama_pic',
        'email',
        'password',
        'role_user',
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
