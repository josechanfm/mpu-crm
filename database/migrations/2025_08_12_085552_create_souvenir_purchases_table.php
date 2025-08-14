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
        Schema::create('souvenir_purchases', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('souvenir_user_id');
            $table->text('form_meta')->nullable();
            $table->json('items')->nullable();
            $table->char('currency',3)->nullable();
            $table->integer('amount')->nullable();
            $table->text('payment_method')->nullable();
            $table->text('payment_meta')->nullable();
            $table->text('transction_code')->nullable();
            $table->text('transection_meta')->nullable();
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
        Schema::dropIfExists('souvenir_purchases');
    }
};
