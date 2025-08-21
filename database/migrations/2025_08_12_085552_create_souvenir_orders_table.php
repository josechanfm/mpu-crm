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
        Schema::create('souvenir_orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('souvenir_user_id');
            $table->foreignId('payment_id');
            $table->text('form_meta')->nullable();
            $table->json('items')->nullable();
            $table->char('currency',3)->nullable();
            $table->integer('amount')->nullable();
            $table->text('payment_method')->nullable();
            $table->string('payment_status')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('souvenir_orders');
    }
};
