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
        Schema::create('rec_notices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rec_vacancy_id');
            $table->string('title_tw');
            $table->string('title_en')->nullable();
            $table->string('title_pt')->nullable();
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->string('file_zh')->nullable();
            $table->string('file_en')->nullable();
            $table->string('file_pt')->nullable();
            $table->boolean('can_download')->default(false);
            $table->boolean('can_apply')->default(false);
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
        Schema::dropIfExists('rec_notices');
    }
};
