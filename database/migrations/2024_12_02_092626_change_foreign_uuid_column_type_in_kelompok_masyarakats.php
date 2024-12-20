<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeForeignUuidColumnTypeInKelompokMasyarakats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kelompok_masyarakats', function (Blueprint $table) {
            //
            $table->string('provinsi_kelompok_masyarakat_id', 50)->nullable()->change();
            $table->string('kabupaten_kelompok_masyarakat_id', 50)->nullable()->change();
            $table->string('kecamatan_kelompok_masyarakat_id', 50)->nullable()->change();
            $table->string('kelurahan_kelompok_masyarakat_id', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kelompok_masyarakats', function (Blueprint $table) {
            //
        });
    }
}
