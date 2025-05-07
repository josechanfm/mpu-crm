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
        Schema::create('rec_professionals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rec_application_id');
            $table->string('organization_name');
            $table->string('region')->nullable();
            $table->string('qualification');
            $table->string('area')->nullable();
            $table->date('date_valid');
            $table->date('date_expire')->nullable();
            $table->string('hours')->nullable();
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
        Schema::dropIfExists('rec_professionals');
    }
};
