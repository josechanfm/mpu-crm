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
        Schema::create('rec_academic_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rec_application_id');
            $table->string('company_name');
            $table->string('company_locale');
            $table->string('position');
            $table->string('salary')->nullable();
            $table->string('emplyment_type');
            $table->string('date_join');
            $table->string('date_leave');
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
        Schema::dropIfExists('rec_academic_experiences');
    }
};
