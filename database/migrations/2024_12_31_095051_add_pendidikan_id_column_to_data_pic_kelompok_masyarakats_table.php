<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPendidikanIdColumnToDataPicKelompokMasyarakatsTable extends Migration
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
            $table->foreignUuid('pendidikan_id')->nullable()->after('jenis_pekerjaan_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_pic_kelompok_masyarakats', function (Blueprint $table) {
            //
        });
    }
}
