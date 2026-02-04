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
        Schema::create('staff_relatives', function (Blueprint $table) {
            $table->id();
            $table->boolean('has_allowance')->nullable();
            $table->boolean('has_medical')->nullable();
            $table->string('medical_num')->nullable();
            $table->string('medical_type')->nullable();
            $table->string('staff_num')->nullable();
            $table->string('name_zh', 45)->nullable();
            $table->string('name_pt', 255)->nullable();
            $table->string('relationship')->nullable();
            $table->string('allowance_type')->nullable();
            $table->string('dob_date')->nullable();
            $table->string('id_num')->nullable();
            $table->string('avatar')->nullable();
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
        Schema::dropIfExists('staff_relatives');
    }
};
