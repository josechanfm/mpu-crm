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
            $table->string('procedure_code')->nullable();
            $table->string('vacancy_code');
            $table->string('category_code');
            $table->string('title_zh');
            $table->string('title_en')->nullable();
            $table->string('title_pt')->nullable();
            $table->text('description')->nullable();
            $table->string('proposal_num')->nullable();
            $table->string('chairman')->nullable();
            $table->foreignId('department_id')->nullable();
            $table->string('department_abbr')->nullable();
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->string('email_notice')->nullable();
            $table->string('handler')->nullable();
            $table->string('handler_email')->nullable();
            $table->char('status',10);
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
