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
        Schema::create('rec_education', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rec_application_id');
            $table->string('school_name');
            $table->string('school_locale');
            $table->string('level');
            $table->string('degree');
            $table->string('subject');
            $table->string('lang');
            $table->string('date_start');
            $table->string('date_end')->nullable();
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
        Schema::dropIfExists('rec_education');
    }
};
