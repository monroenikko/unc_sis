<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentEnrolledSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_enrolled_subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subject_id')->unsigned();
            $table->integer('enrollments_id')->unsigned();
            $table->decimal('fir_g', 5, 2)->default('0.00');
            $table->integer('fir_g_status')->default(0);
            $table->decimal('sec_g', 5, 2)->default('0.00');
            $table->integer('sec_g_status')->default(0);
            $table->decimal('thi_g', 5, 2)->default('0.00');
            $table->integer('thi_g_status')->default(0);
            $table->decimal('fou_g', 5, 2)->default('0.00');
            $table->integer('fou_g_status')->default(0);
            $table->decimal('fin_g', 5, 2)->default('0.00');
            $table->integer('fin_g_status')->default(0);
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
        Schema::dropIfExists('student_enrolled_subjects');
    }
}
