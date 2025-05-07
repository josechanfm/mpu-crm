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
        Schema::create('rec_tasks', function (Blueprint $table) {
            $table->id();
            $table->char('procedure_code',3); //ACA,ADM,ACP
            $table->integer('sequence')->nullable();
            $table->string('vacancy_type');
            $table->string('name');
            $table->string('department_id');
            $table->string('department_abbr');
            $table->integer('days');
            $table->string('email')->nullable();
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
        Schema::dropIfExists('rec_tasks');
    }
};
