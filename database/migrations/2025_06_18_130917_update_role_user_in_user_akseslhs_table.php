<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRoleUserInUserAkseslhsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE akseslh_user_akseslhs MODIFY role_user ENUM('maker', 'verifikator', 'approver', 'pmu-bpdlh', 'administrator')");
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
