<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLogPengajuanKegiatanTableNullTanggalMasuk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('log_tahapan_pengajuan_kegiatans', function (Blueprint $table) {
            $table->date('tanggal_masuk')->nullable()->change();
            $table->foreignUuid('user_akseslh')->after('tanggal_selesai')->nullable();
        });

        Schema::table('user_akseslhs', function (Blueprint $table) {
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
