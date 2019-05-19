<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassSubjectDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_subject_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('class_days')->nullable();
            $table->time('class_time_from')->nullable();
            $table->time('class_time_to')->nullable();
            $table->integer('subject_id')->unsigned();
            $table->integer('faculty_id')->unsigned()->nullable();
            $table->integer('room_id')->unsigned();
            $table->integer('class_details_id')->unsigned();
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
        Schema::dropIfExists('class_subject_details');
    }
}
