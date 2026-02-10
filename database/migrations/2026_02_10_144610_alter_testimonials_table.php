<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTestimonialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('testimonials', function (Blueprint $table) {
            //
            $table->boolean('is_published')->default(false)->after('testimonial');
            $table->dateTime('published_date')->nullable()->after('is_published');
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
        Schema::table('testimonials', function (Blueprint $table) {
            //
            $table->dropColumn('is_published');
            $table->dropColumn('published_date');
        });
    }
}
