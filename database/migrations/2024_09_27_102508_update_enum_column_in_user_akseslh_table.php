<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEnumColumnInUserAkseslhTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE akseslh_user_akseslhs MODIFY role_user ENUM('maker', 'verifikator', 'approver', 'pmu-bpdlh')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_akseslh', function (Blueprint $table) {
            //
        });
    }
}
