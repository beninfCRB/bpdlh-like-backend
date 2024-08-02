<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSomeTableAddCodeIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('tematik_kegiatans', function (Blueprint $table) {
            $table->tinyInteger('code_id')->after('short_id')->default(1);
        });
        Schema::table('sub_tematik_kegiatans', function (Blueprint $table) {
            $table->tinyInteger('code_id')->after('short_id')->default(1);
        });
        Schema::table('jenis_kegiatans', function (Blueprint $table) {
            $table->tinyInteger('code_id')->after('short_id')->default(1);
        });
        Schema::table('jenis_kelompok_masyarakats', function (Blueprint $table) {
            $table->tinyInteger('code_id')->after('short_id')->default(1);
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
