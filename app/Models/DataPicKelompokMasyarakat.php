<?php

namespace App\Models;

use App\Models\AppAuthenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class DataPicKelompokMasyarakat extends AppModel
{
    use HasFactory, Notifiable;

    protected $guard = 'akseslh';

    protected $table = "data_pic_kelompok_masyarakats";

    protected $fillable = [
        'kelompok_masyarakat_id',
        'nama_pic',
        'jenis_identitas_pic',
        'nomor_identitas_pic',
        'email_pic',
        'nohp_pic',
        'alamat_pic',
        'kelurahan_pic',
        'kecamatan_pic',
        'kabupaten_pic',
        'provinsi_pic',
        'flag',
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

    /**
     * Get the user_akseslh associated with the DataPicKelompokMasyarakat
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user_akseslh(): HasOne
    {
        return $this->hasOne(UserAkseslh::class);
    }
}
