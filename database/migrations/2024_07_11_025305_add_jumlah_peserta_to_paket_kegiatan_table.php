<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJumlahPesertaToPaketKegiatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paket_kegiatans', function (Blueprint $table) {
            //
            $table->integer('jumlah_peserta')->after('deskripsi_paket_kegiatan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paket_kegiatan', function (Blueprint $table) {
            //
            $table->dropColumn('jumlah_peserta');
        });
    }
}
