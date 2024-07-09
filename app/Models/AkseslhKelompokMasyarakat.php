<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AkseslhKelompokMasyarakat extends AppModel
{
    use HasFactory;

    protected $table = "kelompok_masyarakats";

    protected $fillable = [
        'akseslh_jenis_kelompok_masyarakat_id',
        'kelompok_masyarakat',
        'flag',
        'username'
    ];

    /**
     * Get the jenis that owns the AkseslhKelompokMasyarakat
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jenis(): BelongsTo
    {
        return $this->belongsTo(AkseslhJenisKelompokMasyarakat::class, 'akseslh_jenis_kelompok_masyarakat_id');
    }
}
