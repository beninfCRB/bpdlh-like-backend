<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Models\AppAuthenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    /**
     * Get all of the master_user_jenis_kelompok for the UserAkseslh
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function master_user_jenis_kelompok(): HasMany
    {
        return $this->hasMany(MasterUserJenisKelompok::class, 'user_akseslh_id');
    }

    /**
     * Get all of the pengajuan_kegiatan for the UserAkseslh
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pengajuan_kegiatan(): HasMany
    {
        return $this->hasMany(PengajuanKegiatan::class, 'user_akseslh_id');
    }

    public function createCustomToken($name = 'custom-token', $abilities = ['*'])
    {
        $plainTextToken = Str::random(40);

        $token = $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken),
            'abilities' => $abilities,
        ]);

        $customToken = $this->id . '_' . $plainTextToken;

        return $customToken; // return tanpa ID dan tanpa '|'
    }
}
