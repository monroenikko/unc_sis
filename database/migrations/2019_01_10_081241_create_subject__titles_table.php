<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectTitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject__titles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('section_id');
            $table->integer('subject_1');
            $table->integer('subject_2');
            $table->integer('subject_3');
            $table->integer('subject_4');
            $table->integer('subject_5');
            $table->integer('subject_6');
            $table->integer('subject_7');
            $table->integer('subject_8');
            $table->integer('subject_9');
            $table->tinyInteger('sem');           
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
        Schema::dropIfExists('subject__titles');
    }
}
