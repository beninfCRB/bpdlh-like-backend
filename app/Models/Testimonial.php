<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Testimonial extends AppModel
{
    use HasFactory;

    protected $table = "testimonials";

    protected $fillable = [
        'data_pic_kelompok_masyarakat_id',
        'pengajuan_kegiatan_id',
        'testimonial',
        'is_published',
        'published_date',
    ];

    /**
     * Get the pengajuan_kegiatan that owns the Testimonial
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pengajuan_kegiatan(): BelongsTo
    {
        return $this->belongsTo(PengajuanKegiatan::class, 'pengajuan_kegiatan_id', 'id');
    }

    /**
     * Get the data_pic_kelompok_masyarakat that owns the Testimonial
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function data_pic_kelompok_masyarakat(): BelongsTo
    {
        return $this->belongsTo(DataPicKelompokMasyarakat::class, 'data_pic_kelompok_masyarakat_id', 'id');
    }
}
