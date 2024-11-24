<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNpwpColumnToPicTable extends Migration
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
            $table->string('nomor_npwp_pic')->after('nomor_identitas_pic')->nullable();
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
