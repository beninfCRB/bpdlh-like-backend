<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameForeignUuidColumnInTablePaketKegiatans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paket_kegiatans', function (Blueprint $table) {
            //rename column
            $table->renameColumn('sub_tematik_kegiatan_id', 'master_sub_tematik_kegiatan_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paket_kegiatans', function (Blueprint $table) {
            //
        });
    }
}
