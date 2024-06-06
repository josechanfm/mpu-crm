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
        Schema::create('rec_workflows', function (Blueprint $table) {
            $table->id();
            $table->string('vacancy_code');
            $table->string('category_code');
            $table->string('title_c');
            $table->string('title_e');
            $table->string('title_p');
            $table->text('description');
            $table->string('procedure_code');
            $table->string('proposal_num');
            $table->string('Chairman');
            $table->string('department_id');
            $table->date('date_start');
            $table->date('date_end');
            $table->string('email_notice');
            $table->string('handler');
            $table->string('handler_email');
            $table->char('status',3);
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
        Schema::dropIfExists('rec_workflows');
    }
};
