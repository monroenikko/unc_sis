<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_details', function (Blueprint $table) {
            $table->increments('id');
            $table->time('class_time')->nullable();
            $table->string('class_schedule', 20)->nullable();
            $table->integer('faculty_id')->unsigned();
            $table->integer('subject_id')->unsigned();
            $table->integer('section_id')->unsigned();
            $table->integer('room_id')->unsigned();
            $table->integer('school_year_id')->unsigned();
            $table->integer('grade_level')->unsigned();
            $table->tinyInteger('current')->default('1');
            $table->tinyInteger('status')->default('1');
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
        Schema::dropIfExists('class_details');
    }
}
