<?php

namespace App\Models;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class AkseslhUserEksternal extends AppModel
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = "akseslh_user_eksternals";

    protected $fillable = [
        'akseslh_kelompok_masyarakat_id',
        'email_user_eksternal',
        'password_user_eksternal',
        'nama_user_eksternal',
        'jenis_identitas_user_eksternal',
        'nomor_identitas_user_eksternal',
        'nomor_hp_user_eksternal',
        'username',
    ];
}
