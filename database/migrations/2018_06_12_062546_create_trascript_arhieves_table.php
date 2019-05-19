<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrascriptArhievesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trascript_arhieves', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name', 191)->nullable();
            $table->string('middle_name', 191)->nullable();
            $table->string('last_name', 191)->nullable();
            $table->integer('school_year_graduated')->nullable();
            $table->string('file_name', 191)->nullable();
            $table->tinyInteger('status')->default(1)->nullble();
            // $table->string('personal_information', 191)->nullable();
            // $table->text('credentials_information')->nullable();
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
        Schema::dropIfExists('trascript_arhieves');
    }
}
