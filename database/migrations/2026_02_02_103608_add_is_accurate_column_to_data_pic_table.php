<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsAccurateColumnToDataPicTable extends Migration
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
            $table->boolean('is_accurate')->default(false)->after('alamat_kontak_darurat');
            $table->dateTime('accurate_date')->nullable()->after('is_accurate');
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
            $table->dropColumn('is_accurate');
            $table->dropColumn('accurate_date');
        });
    }
}
