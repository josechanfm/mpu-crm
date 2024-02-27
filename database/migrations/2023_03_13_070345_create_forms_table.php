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
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id');
            $table->string('name');
            $table->string('title')->nullable();
            $table->text('welcome')->nullable();
            $table->text('description')->nullable();
            $table->text('thanks')->nullable();
            $table->boolean('require_login')->default(false);
            $table->boolean('for_staff')->default(false);
            $table->boolean('published')->default(false);
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
        Schema::dropIfExists('forms');
    }
};
