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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('abbr')->nullable();
            $table->string('name_zh')->nullable();
            $table->string('name_en')->nullable();
            $table->string('name_pt')->nullable();
            $table->text('landing_page')->nullable();
            $table->string('phone')->nullable();
            $table->string('href')->nullable();
            $table->string('default_route')->nullable();
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
        Schema::dropIfExists('departments');
    }
};
