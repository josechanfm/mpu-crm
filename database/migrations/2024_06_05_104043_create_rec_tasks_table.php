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
        Schema::create('rec_tasks', function (Blueprint $table) {
            $table->id();
            $table->char('category_code',3); //ACA,ADM,ACP
            $table->integer('sequence')->nullable();
            $table->string('name');
            $table->string('department_id');
            $table->integer('days');
            $table->string('email');
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
        Schema::dropIfExists('rec_tasks');
    }
};
