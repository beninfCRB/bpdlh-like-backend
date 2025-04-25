<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PivotEmailBlast extends Model
{
    use HasFactory;

    protected $fillable = ['nomor_pengajuan', 'email', 'status', 'sent_at'];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanKegiatan::class, 'nomor_pengajuan', 'nomor_pengajuan');
    }
}
