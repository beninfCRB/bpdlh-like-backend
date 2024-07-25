<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNomorPengajuanInPengajuanKegiatans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengajuan_kegiatans', function (Blueprint $table) {
            $table->string("nomor_pengajuan", 50)->after("id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pengajuan_kegiatans', function (Blueprint $table) {
            $table->dropColumn('nomor_pengajuan');
        });
    }
}
