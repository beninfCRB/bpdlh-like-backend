<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAkseslhsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_akseslhs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('data_pic_kelompok_masyarakat_id');
            $table->string('email', 50)->unique();
            $table->string('password', 150);
            $table->enum('status_user', ['ACTIVE', 'NON ACTIVE']);
            $table->tinyInteger('flag');
            $table->string('username', 100)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_akseslhs');
    }
}
