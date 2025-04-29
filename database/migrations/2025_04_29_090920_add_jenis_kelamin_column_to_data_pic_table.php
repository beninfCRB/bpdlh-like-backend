<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJenisKelaminColumnToDataPicTable extends Migration
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
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan'])->nullable()->after('pendidikan_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_pic', function (Blueprint $table) {
            //
        });
    }
}
