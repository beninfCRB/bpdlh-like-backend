<?php

namespace App\Models;

use App\Models\File;
use App\Models\AppModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransaksiPenyaluran extends AppModel
{
    use HasFactory;

    protected $table = 'transaksi_penyalurans';

    protected $fillable = [
        'master_data_bank_id',
        'pengajuan_kegiatan_id',
        'nomor_rekening',
        'nama_pemilik_rekening',
        'nilai_penyaluran',
        'tanggal_penyaluran',
        'flag',
        'username',
    ];

    /**
     * Get the master_data_bank that owns the TransaksiPenyaluran
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function master_data_bank(): BelongsTo
    {
        return $this->belongsTo(MasterDataBank::class, 'master_data_bank_id');
    }

    /**
     * Get the pengajuan_kegiatan that owns the TransaksiPenyaluran
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pengajuan_kegiatan(): BelongsTo
    {
        return $this->belongsTo(PengajuanKegiatan::class, 'pengajuan_kegiatan_id');
    }

    /**
     * Get the user that owns the TransaksiPenyaluran
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserAkseslh::class, 'username');
    }

    public function document()
    {
        return $this->morphMany(File::class, 'fileable')
            ->select(['id', 'group', 'visibility', 'file_name', 'file_path', 'fileable_id']);
    }
}
