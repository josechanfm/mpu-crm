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
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
            $table->id(); // auto-incrementing id
            $table->string('ip_address', 15)->nullable();
            $table->string('username', 100)->unique()->nullable();
            $table->string('password', 255)->nullable();
            $table->string('salt', 255)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('activation_code', 40)->nullable();
            $table->string('forgotten_password_code', 40)->nullable();
            $table->unsignedInteger('forgotten_password_time')->nullable();
            $table->string('remember_code', 40)->nullable();
            $table->unsignedInteger('created_on')->nullable();
            $table->unsignedInteger('last_login')->nullable();
            $table->boolean('active')->nullable();
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('company', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('name_zh', 45)->nullable();
            $table->string('name_pt', 255)->nullable();
            $table->string('staff_num', 45)->nullable();
            $table->char('medical_type', 1);
            $table->string('medical_num', 12);
            $table->string('library_num', 255);
            $table->date('issue_date');
            $table->char('employment', 1);
            $table->boolean('lecturer');
            $table->string('dept', 45)->nullable();            
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
        Schema::dropIfExists('staffs');
    }
};
