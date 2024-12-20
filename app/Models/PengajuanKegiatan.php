<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'lokasi_bidang_folu_id',
        'flag'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'flag' => 'string',
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
    public function user_akseslh(): BelongsTo
    {
        return $this->belongsTo(UserAkseslh::class, 'user_akseslh_id');
    }

    public static function generateNomorPengajuan($paket_kegiatan_id, $user)
    {
        $paket_kegiatan = PaketKegiatan::find($paket_kegiatan_id);
        $jenis_kelompok = str_pad($user->data_pic_kelompok_masyarakat->kelompok_masyarakat->jenis->code_id, 2, "0", STR_PAD_LEFT);
        $tematik_kegiatan = $paket_kegiatan->master_sub_tematik_kegiatan->tematik_kegiatan->code_id;
        $sub_tematik_kegiatan = $paket_kegiatan->master_sub_tematik_kegiatan->sub_tematik_kegiatan->code_id;
        $paket_kegiatan = $paket_kegiatan->jenis_kegiatan->code_id ? $paket_kegiatan->jenis_kegiatan->code_id : 0;
        $tahun = Carbon::now()->format('y');
        $bulan = Carbon::now()->format('m');
        $lastRecord = self::latest()->first();

        $lastNumber = $lastRecord ? intval(substr($lastRecord->nomor_pengajuan, -4)) : 0;
        $lastNumber += 1;
        $newNumber = str_pad($lastNumber, 5, '0', STR_PAD_LEFT);

        return $jenis_kelompok . $tematik_kegiatan . $sub_tematik_kegiatan . $paket_kegiatan . "-" . $tahun . $bulan . "-" . $newNumber;
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

    /**
     * Get all of the detail_log_tahapan_pengajuan for the PengajuanKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detail_log_tahapan_pengajuan(): HasMany
    {
        return $this->hasMany(DetailLogTahapanPengajuanKegiatan::class, 'pengajuan_kegiatan_id');
    }

    public function document()
    {
        return $this->morphMany(File::class, 'fileable')
            ->select(['id', 'group', 'visibility', 'file_name', 'file_path', 'fileable_id']);
    }

    /**
     * Get all of the rab_pengajuan_paket_kegiatans for the PengajuanKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rab_pengajuan_paket_kegiatans(): HasMany
    {
        return $this->hasMany(RabPengajuanPaketKegiatan::class, 'pengajuan_kegiatan_id');
    }

    /**
     * Get all of the rab_pengajuan_paket_kegiatans for the PengajuanKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function log_rab_pengajuan_paket_kegiatan(): HasMany
    {
        return $this->hasMany(LogRabPengajuanPaketKegiatan::class, 'pengajuan_kegiatan_id');
    }

    /**
     * Get the provinsi that owns the PengajuanKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'provinsi_kegiatan');
    }

    /**
     * Get the kabupaten that owns the PengajuanKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(City::class, 'kabupaten_kegiatan');
    }

    /**
     * Get the kecamatan that owns the PengajuanKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(District::class, 'kecamatan_kegiatan');
    }

    /**
     * Get the kelurahan that owns the PengajuanKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'kelurahan_kegiatan');
    }

    /**
     * Get all of the transaksi_penyaluran for the PengajuanKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transaksi_penyaluran(): HasMany
    {
        return $this->hasMany(TransaksiPenyaluran::class, 'pengajuan_kegiatan_id');
    }

    /**
     * Get all of the indikator_laporan_kegiatan for the PengajuanKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function indikator_laporan_kegiatan(): HasMany
    {
        return $this->hasMany(IndikatorLaporanKegiatan::class, 'pengajuan_kegiatan_id');
    }

    /**
     * Get the pengembalian associated with the PengajuanKegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function pengembalian(): HasOne
    {
        return $this->hasOne(Pengembalian::class, 'pengajuan_kegiatan_id');
    }
}
