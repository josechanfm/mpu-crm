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
        Schema::create('language_materials', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // e.g., "Daily Routine"
            $table->string('type', 20); // e.g., "speach", "vocabulary"
            $table->string('level', 5); // A1, A2, B1, B2
            $table->text('content'); // The English paragraph
            $table->text('translation')->nullable(); // Traditional Chinese translation
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
        Schema::dropIfExists('language_materials');
    }
};
