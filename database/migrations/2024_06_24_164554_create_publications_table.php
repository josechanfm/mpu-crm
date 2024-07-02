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
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->nullable();
            $table->string('category')->nullable();
            $table->string('title_zh')->nullable();
            $table->string('title_pt')->nullable();
            $table->string('title_en')->nullable();
            $table->text('description_zh')->nullable();
            $table->text('description_pt')->nullable();
            $table->text('description_en')->nullable();
            $table->string('author')->nullable();
            $table->text('print')->nullable();
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
        Schema::dropIfExists('publications');
    }
};
