<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AppModel;

class Testimonial extends AppModel
{
    use HasFactory;

    protected $table = "testimonials";

    protected $fillable = [
        'data_pic_kelompok_masyarakat_id',
        'pengajuan_kegiatan_id',
        'testimonial',
    ];
}
