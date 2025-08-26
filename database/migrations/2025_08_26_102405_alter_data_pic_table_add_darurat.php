<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDataPicTableAddDarurat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('data_pic_kelompok_masyarakats', function (Blueprint $table) {
            //
            $table->text('nama_kontak_darurat')->nullable()->after('nohp_pic');
            $table->text('nomor_kontak_darurat')->nullable()->after('nama_kontak_darurat');
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
