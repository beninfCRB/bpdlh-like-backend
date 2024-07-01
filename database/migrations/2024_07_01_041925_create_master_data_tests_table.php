<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterDataTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_data_tests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('test_name')->nullable();
            $table->boolean('flag')->default(false);
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
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
        Schema::dropIfExists('master_data_tests');
    }
}
