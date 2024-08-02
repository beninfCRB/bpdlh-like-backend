<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUserAkseslhTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('user_akseslhs', function (Blueprint $table) {
            //
            $table->foreignUuid('data_pic_kelompok_masyarakat_id')->nullable()->change();
            $table->enum('role_user', ['maker', 'verifikator', 'approver'])->default('maker')->after('status_user');
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
