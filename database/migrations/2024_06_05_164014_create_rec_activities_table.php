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
        Schema::create('rec_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rec_workflow_id');
            $table->integer('sequence')->nullable();
            $table->string('vacancy_code')->nullable();
            $table->string('name');
            $table->integer('days');
            $table->string('department_id')->nullable();
            $table->string('department_abbr')->nullable();
            $table->string('email')->nullable();
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->date('target_start')->nullable();
            $table->date('target_end')->nullable();
            $table->boolean('active');
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
        Schema::dropIfExists('rec_activities');
    }
};
