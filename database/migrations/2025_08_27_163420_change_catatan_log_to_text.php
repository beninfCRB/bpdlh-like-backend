<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCatatanLogToText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catatan_log_tahapan_pengajuan_kegiatans', function (Blueprint $table) {
            $table->text('catatan_log')->change();
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
            $table->string('catatan_log')->change();
        });
    }
}
