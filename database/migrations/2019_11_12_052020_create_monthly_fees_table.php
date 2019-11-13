<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonthlyFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_fees', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('grade_level_id');
            $table->integer('tuition_fee_id');
            $table->integer('misc_fee_id');
            $table->double('monthly_fee', 191);
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
        Schema::dropIfExists('monthly_fees');
    }
}
