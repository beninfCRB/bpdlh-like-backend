<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PivotEmailBlast extends Model
{
    use HasFactory;

    protected $fillable = ['pengajuan_kegiatan_id', 'email', 'status', 'sent_at'];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanKegiatan::class, 'pengajuan_kegiatan_id');
    }
}
