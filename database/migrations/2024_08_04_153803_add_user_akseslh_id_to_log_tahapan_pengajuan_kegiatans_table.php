<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserAkseslhIdToLogTahapanPengajuanKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_tahapan_pengajuan_kegiatans', function (Blueprint $table) {
            //
            $table->foreignUuid('user_akseslh_id')->after('tanggal_selesai')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_tahapan_pengajuan_kegiatans', function (Blueprint $table) {
            //
        });
    }
}
