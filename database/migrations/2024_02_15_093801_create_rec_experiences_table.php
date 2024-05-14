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
        Schema::create('rec_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rec_application_id');
            $table->string('company_name');
            $table->string('region');
            $table->string('position');
            $table->string('salary')->nullable();
            $table->string('employment');
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
        Schema::dropIfExists('rec_experiences');
    }
};
