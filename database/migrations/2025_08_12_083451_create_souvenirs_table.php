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
        Schema::create('souvenirs', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('description')->nullable(); 
            $table->integer('stock')->default(500);
            $table->integer('price')->default(100); 
            $table->integer('quota')->default(3);
            $table->boolean('is_available')->default(false); 
            $table->string('thumbnail')->nullable();
            $table->text('images')->nullable();
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
        Schema::dropIfExists('souvenirs');
    }
};
