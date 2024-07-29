<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengajuanKegiatan extends AppModel
{
    use HasFactory;

    protected $table = "pengajuan_kegiatans";

    protected $fillable = [
        'nomor_pengajuan',
        'paket_kegiatan_id',
        'user_akseslh_id',
        'judul_pengajuan_kegiatan',
        'provinsi_kegiatan',
        'kabupaten_kegiatan',
        'kecamatan_kegiatan',
        'kelurahan_kegiatan',
        'alamat_kegiatan',
        'tanggal_mulai_kegiatan',
        'tanggal_akhir_kegiatan',
        'time_mulai_kegiatan',
        'time_akhir_kegiatan',
        'proposal_kegiatan',
        'tujuan_kegiatan',
        'ruang_lingkup_kegiatan',
        'username',
    ];

    /**
     * Get the jenis_kegiatan that owns the AkseslhPaketKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paket_kegiatan(): BelongsTo
    {
        return $this->belongsTo(PaketKegiatan::class, 'paket_kegiatan_id');
    }

    /**
     * Get the tematik_kegiatan that owns the AkseslhPaketKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user_eksternal(): BelongsTo
    {
        return $this->belongsTo(UserEksternal::class, 'akseslh_user_eksternal_id');
    }

    public static function generateNomorPengajuan()
    {
        $dateTime = now()->format('YmdHi');
        $lastRecord = self::whereDate('created_at', now()->toDateString())
            ->orderBy('id', 'desc')
            ->first();
        $lastNumber = $lastRecord ? intval(substr($lastRecord->nomor_pengajuan, -4)) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return $dateTime . '' . $newNumber;
    }

    /**
     * Get all of the log_tahapan_pengajuan for the PengajuanKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function log_tahapan_pengajuan(): HasMany
    {
        return $this->hasMany(LogTahapanPengajuanKegiatan::class, 'pengajuan_kegiatan_id');
    }

    public function document()
    {
        return $this->morphOne(File::class, 'fileable')
            ->select(['id', 'group', 'visibility', 'file_name', 'file_path', 'fileable_id']);
    }
}
