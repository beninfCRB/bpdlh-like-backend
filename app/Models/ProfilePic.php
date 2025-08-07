<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProfilePic extends AppModel
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'profile_pics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kelompok_masyarakat_id',
        'nama_pic',
        'jenis_identitas_pic',
        'nomor_identitas_pic',
        'nomor_npwp_pic',
        'email_pic',
        'nohp_pic',
        'alamat_pic',
        'kelurahan_pic',
        'kecamatan_pic',
        'kabupaten_pic',
        'provinsi_pic',
        'tempat_lahir',
        'tanggal_lahir',
        'agama_id',
        'status_perkawinan_id',
        'nama_gadis_ibu_kandung',
        'jenis_pekerjaan_id',
        'pendidikan_id',
        'jenis_kelamin',
        'flag',
        'username',
    ];
}
