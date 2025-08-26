<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProfilePicsTableMakeNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('profile_pics', function (Blueprint $table) {
            //
            $table->foreignUuid('kelompok_masyarakat_id')->nullable()->change();
            $table->string('nama_pic', 255)->nullable()->change();
            $table->string('nomor_identitas_pic', 50)->nullable()->change();
            $table->string('nohp_pic', 50)->nullable()->change();
            $table->string('alamat_pic', 255)->nullable()->change();
            $table->string('kelurahan_pic', 50)->nullable()->change();
            $table->string('kecamatan_pic', 50)->nullable()->change();
            $table->string('kabupaten_pic', 50)->nullable()->change();
            $table->string('provinsi_pic', 50)->nullable()->change();
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
