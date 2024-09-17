<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBatasPengajuanToLogJadwalPembukaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_jadwal_pembukaans', function (Blueprint $table) {
            //
            $table->bigInteger('batas_pengajuan')->default(0)->after('jam_akhir');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_jadwal_pembukaans', function (Blueprint $table) {
            //
        });
    }
}
