<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDateRemarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('date_remarks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('school_year_id');
            $table->date('j_date');
            $table->date('s_date1');
            $table->date('s_date2');
            $table->tinyInteger('current')->default('0');
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
        Schema::dropIfExists('date_remarks');
    }
}
