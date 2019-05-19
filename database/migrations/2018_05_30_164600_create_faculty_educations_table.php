<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacultyEducationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faculty_educations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('course', 191)->nullable();
            $table->string('school', 191)->nullable();
            $table->string('awards', 191)->nullable();
            $table->date('from')->nullable();
            $table->date('to')->nullable();
            $table->integer('faculty_id')->unsigned();
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
        Schema::dropIfExists('faculty_educations');
    }
}
