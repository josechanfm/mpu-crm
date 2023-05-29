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
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->char('lang',2);
            $table->string('origin')->nullable();
            $table->string('degree')->nullable();
            $table->string('admission')->nullable();
            $table->string('profile')->nullable();
            $table->string('apply')->nullable();
            $table->string('surname')->nullable();
            $table->string('givenname')->nullable();
            $table->string('email')->nullable();
            $table->string('areacode')->nullable();
            $table->string('phone')->nullable();
            $table->string('subjects')->nullable();
            $table->string('token')->nullable();
            $table->text('question')->nullable();
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
        Schema::dropIfExists('inquiries');
    }
};
