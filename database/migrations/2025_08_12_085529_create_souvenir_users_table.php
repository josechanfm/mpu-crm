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
        Schema::create('souvenir_users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('notify_email');
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('netid');
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('faculty_code')->nullable();
            $table->string('degree_code')->nullable();
            $table->integer('grad_year')->nullable();
            $table->text('remark')->nullable();
            $table->boolean('can_buy')->default(false);
            $table->string('token')->nullable();
            $table->integer('notify_sent')->default(0);
            $table->string('email_verification_code')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('active')->default(false);
            $table->text('meta_data')->nullable();
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
        Schema::dropIfExists('souvenir_users');
    }
};
