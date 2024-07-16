<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTematikKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('tematik_kegiatans', function (Blueprint $table) {
            //
            $table->dropColumn('icon_tematik');
            $table->string('deskripsi_tematik', 255)->nullable()->after('short_id');
            $table->tinyInteger('flag')->after('deskripsi_tematik');
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
