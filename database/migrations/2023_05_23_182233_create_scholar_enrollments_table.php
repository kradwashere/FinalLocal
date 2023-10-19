<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholar_enrollments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->boolean('is_enrolled')->default(0);
            $table->boolean('is_delayed')->default(0);
            $table->boolean('is_checked')->default(0);
            $table->boolean('is_missed')->default(0);
            $table->boolean('is_grades_completed')->default(0);
            $table->boolean('is_benefits_released')->default(0);
            $table->boolean('is_completed')->default(0);
            $table->boolean('is_locked')->default(0);
            $table->json('attachment');
            $table->integer('semester_id')->unsigned()->index();
            $table->foreign('semester_id')->references('id')->on('school_semesters')->onDelete('cascade');
            $table->tinyInteger('level_id')->unsigned()->index();
            $table->foreign('level_id')->references('id')->on('list_dropdowns')->onDelete('cascade');
            $table->bigInteger('scholar_id')->unsigned()->index();
            $table->foreign('scholar_id')->references('id')->on('scholars')->onDelete('cascade');
            $table->bigInteger('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('scholar_enrollments');
    }
};
