<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNamaPicToUserAkseslhsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_akseslhs', function (Blueprint $table) {
            //
            $table->string('nama_pic')->nullable()->after('data_pic_kelompok_masyarakat_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_akseslhs', function (Blueprint $table) {
            //
        });
    }
}
