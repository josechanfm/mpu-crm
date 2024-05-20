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
        Schema::create('rec_vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('code');
            $table->string('title_zh');
            $table->string('title_en')->nullable();
            $table->string('title_pt')->nullable();
            $table->string('education')->nullable();
            $table->string('vehicle')->nullable();
            $table->string('apply_in')->nullable();
            $table->string('apply_title')->nullable();
            $table->text('description')->nullable();
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->date('supplement_start')->nullable();
            $table->date('supplement_end')->nullable();
            $table->string('date_publish')->nullable();
            $table->string('attach_zh')->nullable();
            $table->string('attach_en')->nullable();
            $table->string('attach_pt')->nullable();
            $table->boolean('progress')->nullable();
            $table->boolean('active')->nullable();
            $table->integer('fee')->nullable();
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
        Schema::dropIfExists('rec_vacancies');
    }
};

