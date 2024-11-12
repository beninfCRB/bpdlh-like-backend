<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class MasterUserJenisKelompok extends AppModel
{
    use HasFactory;

    protected $table = 'master_sub_tematik_kegiatans';

    protected $fillable = [
        'user_akseslh_id',
        'jenis_kelompok_masyarakat_id',
        'flag',
    ];

    /**
     * Get the user_akseslh that owns the MasterUserJenisKelompok
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user_akseslh(): BelongsTo
    {
        return $this->belongsTo(UserAkseslh::class, 'user_akseslh_id');
    }

    /**
     * Get the jenis_kelompok that owns the MasterUserJenisKelompok
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jenis_kelompok_masyarakat(): BelongsTo
    {
        return $this->belongsTo(JenisKelompokMasyarakat::class, 'jenis_kelompok_masyarakat_id');
    }
}
