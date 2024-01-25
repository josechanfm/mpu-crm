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
        Schema::create('gpdps', function (Blueprint $table) {
            $table->id();
            $table->char('staff_num',6)->nullable();
            $table->string('name_zh')->nullable();
            $table->string('name_fr')->nullable();
            $table->string('id_num')->nullable();
            $table->string('current_position')->nullable();
            $table->string('current_department')->nullable();
            $table->string('original_position')->nullable();
            $table->string('original_department')->nullable();
            $table->string('employment_type')->nullable();
            $table->date('date_start');
            $table->date('date_remind');
            $table->date('date_due');
            $table->string('email');
            $table->boolean('is_valid')->default(false);
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
        Schema::dropIfExists('gpdps');
    }
};
