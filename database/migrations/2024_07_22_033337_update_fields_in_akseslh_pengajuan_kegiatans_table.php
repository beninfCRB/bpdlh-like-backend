<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFieldsInAkseslhPengajuanKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengajuan_kegiatans', function (Blueprint $table) {

            $table->dropColumn('flag');
            $table->dropColumn('paket_kegitan_id');
        });

        Schema::table('pengajuan_kegiatans', function (Blueprint $table) {
            $table->char('paket_kegiatan_id', 36)->after('id');
            $table->tinyInteger('flag')->default(1)->after('ruang_lingkup_kegiatan');
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
        });
    }
}
