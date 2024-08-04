<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPengajuanKegiatanIdToCatatanLogTahapanPengajuanKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catatan_log_tahapan_pengajuan_kegiatans', function (Blueprint $table) {
            //
            $table->dropColumn('log_tahapan_pengajuan_kegiatan_id');
            $table->foreignUuid('pengajuan_kegiatan_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catatan_log_tahapan_pengajuan_kegiatans', function (Blueprint $table) {
            //
        });
    }
}
