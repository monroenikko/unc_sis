<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradeSheetFirstsemsecondsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grade_sheet_firstsemseconds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('enrollment_id')->unsigned();
            $table->integer('section_details_id')->unsigned();
            $table->decimal('subject_1', 5, 0)->default('0');
            $table->decimal('subject_2', 5, 0)->default('0');
            $table->decimal('subject_3', 5, 0)->default('0');
            $table->decimal('subject_4', 5, 0)->default('0');
            $table->decimal('subject_5', 5, 0)->default('0');
            $table->decimal('subject_6', 5, 0)->default('0');
            $table->decimal('subject_7', 5, 0)->default('0');
            $table->decimal('subject_8', 5, 0)->default('0');
            $table->decimal('subject_9', 5, 0)->default('0');
            $table->integer('current')->default(1);
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('grade_sheet_firstsemseconds');
    }
}
