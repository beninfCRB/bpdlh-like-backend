<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlamatKontakDaruratToPicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_pic_kelompok_masyarakats', function (Blueprint $table) {
            //
            $table->text('alamat_kontak_darurat')->nullable()->after('nomor_kontak_darurat')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pic', function (Blueprint $table) {
            //
        });
    }
}
