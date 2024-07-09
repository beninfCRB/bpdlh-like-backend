<?php

namespace App\Models;

use App\Models\AppModel;
use Laravel\Sanctum\HasApiTokens;
use App\Models\AppAuthenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AkseslhUserEksternal extends AppAuthenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'akseslh';

    protected $table = "user_eksternals";

    protected $fillable = [
        'akseslh_kelompok_masyarakat_id',
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
        return $this->belongsTo(AkseslhKelompokMasyarakat::class, 'akseslh_kelompok_masyarakat_id');
    }
}
