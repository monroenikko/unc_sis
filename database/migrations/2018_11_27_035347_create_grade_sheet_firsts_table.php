<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradeSheetFirstsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grade_sheet_firsts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('enrollment_id')->unsigned();;
            $table->integer('section_details_id')->unsigned();;
            $table->decimal('filipino', 5, 0)->default('0');
            $table->decimal('english', 5, 0)->default('0');
            $table->decimal('math', 5, 2)->default('0');
            $table->decimal('science', 5, 0)->default('0');
            $table->decimal('ap', 5, 0)->default('0');
            $table->decimal('ict', 5, 0)->default('0');
            $table->decimal('mapeh', 5, 0)->default('0');
            $table->decimal('esp', 5, 0)->default('0');
            $table->decimal('religion', 5, 0)->default('0');
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
        Schema::dropIfExists('grade_sheet_firsts');
    }
}
