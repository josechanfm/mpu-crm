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
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id');
            $table->char('lang',2)->nullable();
            $table->string('origin')->nullable();
            $table->string('degree')->nullable();
            $table->string('admission')->nullable();
            $table->string('profile')->nullable();
            $table->boolean('apply')->default(false);
            $table->string('surname')->nullable();
            $table->string('givenname')->nullable();
            $table->string('email')->nullable();
            $table->string('areacode')->nullable();
            $table->string('phone')->nullable();
            $table->string('subjects')->nullable();
            $table->boolean('has_question')->nullable();
            $table->string('token')->nullable();
            $table->boolean('is_closed')->default(0);
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
        Schema::dropIfExists('enquiries');
    }
};
