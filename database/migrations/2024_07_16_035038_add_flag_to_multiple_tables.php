<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFlagToMultipleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('satuans', function (Blueprint $table) {
            $table->dropColumn('flag');
        });

        Schema::table('jenis_kelompok_masyarakats', function (Blueprint $table) {
            $table->dropColumn('flag');
        });

        Schema::table('kelompok_masyarakats', function (Blueprint $table) {
            $table->dropColumn('flag');
        });
        
        Schema::table('master_data_tests', function (Blueprint $table) {
            $table->dropColumn('flag');
        });
        
        Schema::table('satuans', function (Blueprint $table) {
            $table->tinyInteger('flag')->default(1);
        });

        Schema::table('jenis_kelompok_masyarakats', function (Blueprint $table) {
            $table->tinyInteger('flag')->default(1);
        });

        Schema::table('kelompok_masyarakats', function (Blueprint $table) {
            $table->tinyInteger('flag')->default(1);
        });
        
        Schema::table('master_data_tests', function (Blueprint $table) {
            $table->tinyInteger('flag')->default(1);
        });

        // Tambahkan tabel lainnya sesuai kebutuhan
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        

        // Tambahkan tabel lainnya sesuai kebutuhan
    }
}

