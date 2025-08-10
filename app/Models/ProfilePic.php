<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'data_pic_kelompok_masyarakat_id',
        'kelompok_masyarakat',
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
        'catatan',
        'status_verifikasi',
        'flag',
        'username',
    ];

    /**
     * Get the data_pic_kelompok_masyarakat that owns the ProfilePic
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function data_pic_kelompok_masyarakat(): BelongsTo
    {
        return $this->belongsTo(DataPicKelompokMasyarakat::class, 'data_pic_kelompok_masyarakat_id', 'id');
    }

    /**
     * Get the agama that owns the DataPicKelompokMasyarakat
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agama(): BelongsTo
    {
        return $this->belongsTo(Agama::class, 'agama_id');
    }

    /**
     * Get the status_perkawinan that owns the DataPicKelompokMasyarakat
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status_perkawinan(): BelongsTo
    {
        return $this->belongsTo(StatusPernikahan::class, 'status_perkawinan_id');
    }

    /**
     * Get the jenis_pekerjaan that owns the DataPicKelompokMasyarakat
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jenis_pekerjaan(): BelongsTo
    {
        return $this->belongsTo(JenisPekerjaan::class, 'jenis_pekerjaan_id');
    }

    /**
     * Get the pendidikan that owns the DataPicKelompokMasyarakat
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pendidikan(): BelongsTo
    {
        return $this->belongsTo(Pendidikan::class, 'pendidikan_id');
    }

    /**
     * Get the provinsi that owns the PengajuanKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'provinsi_pic');
    }

    /**
     * Get the kabupaten that owns the PengajuanKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(City::class, 'kabupaten_pic');
    }

    /**
     * Get the kecamatan that owns the PengajuanKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(District::class, 'kecamatan_pic');
    }

    /**
     * Get the kelurahan that owns the PengajuanKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'kelurahan_pic');
    }

    public function document()
    {
        return $this->morphMany(File::class, 'fileable')
            ->select(['id', 'group', 'visibility', 'file_name', 'file_path', 'fileable_id', 'real_name', 'created_at']);
    }
}
