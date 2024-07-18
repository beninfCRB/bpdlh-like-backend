<?php

namespace App\Models;

use App\Models\AppModel;
use Laravel\Sanctum\HasApiTokens;
use App\Models\AppAuthenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserEksternal extends AppAuthenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'akseslh';

    protected $table = "user_eksternals";

    protected $fillable = [
        'kelompok_masyarakat_id',
        'email_user_eksternal',
        'password_user_eksternal',
        'nama_user_eksternal',
        'jenis_identitas_user_eksternal',
        'nomor_identitas_user_eksternal',
        'nomor_hp_user_eksternal',
        'username',
    ];

    /**
     * Get the kelompok_masyarakat that owns the AkseslhUserEksternal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kelompok_masyarakat(): BelongsTo
    {
        return $this->belongsTo(KelompokMasyarakat::class, 'kelompok_masyarakat_id');
    }
}
