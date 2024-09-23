<?php

namespace App\Models;

use App\Models\AppModel;
use App\Models\MasterDataBank;
use App\Models\LogTahapanPengajuanKegiatan;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InformasiPencairanDana extends AppModel
{
    use HasFactory;

    protected $table = 'informasi_pencairan_danas';

    protected $fillable = [
        'master_data_bank_id',
        'log_tahapan_pengajuan_kegiatan_id',
        'nama_cabang',
        'jenis_rekening',
        'nama_pemilik_rekening',
        'nomor_rekening'
    ];

    /**
     * Get all of the data_bank for the MasterDataBank
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function data_bank(): HasMany
    {
        return $this->hasMany(MasterDataBank::class, 'master_data_bank_id');
    }

    /**
     * Get all of the log_tahapan_pengajuan_kegiatan for the LogTahapanPengajuanKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function log_tahapan_pengajuan_kegiatan(): HasMany
    {
        return $this->hasMany(LogTahapanPengajuanKegiatan::class, 'log_tahapan_pengajuan_kegiatan_id');
    }
}
