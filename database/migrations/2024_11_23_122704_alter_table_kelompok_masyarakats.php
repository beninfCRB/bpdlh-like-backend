<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableKelompokMasyarakats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('kelompok_masyarakats', function (Blueprint $table) {
            //
            $table->foreignUuid('provinsi_kelompok_masyarakat_id')->nullable()->after('kelompok_masyarakat');
            $table->foreignUuid('kabupaten_kelompok_masyarakat_id')->nullable()->after('provinsi_kelompok_masyarakat_id');
            $table->foreignUuid('kecamatan_kelompok_masyarakat_id')->nullable()->after('kabupaten_kelompok_masyarakat_id');
            $table->foreignUuid('kelurahan_kelompok_masyarakat_id')->nullable()->after('kecamatan_kelompok_masyarakat_id');
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
