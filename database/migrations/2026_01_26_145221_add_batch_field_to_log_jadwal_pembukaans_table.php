<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBatchFieldToLogJadwalPembukaansTable extends Migration
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
            $table->string('batch')->nullable()->after('batas_pengajuan');
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
            $table->dropColumn('batch');
        });
    }
}
