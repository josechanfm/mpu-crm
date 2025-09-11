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
        Schema::create('shorteners', function (Blueprint $table) {
            $table->id();
            $table->string('token')->uniqid();
            $table->string('title');
            $table->text('url');
            $table->date('valid_at')->nullable();
            $table->date('expire_at')->nullable();
            $table->foreignId('admin_user_id');
            $table->text('remark')->nullable();
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
        Schema::dropIfExists('shorteners');
    }
};
