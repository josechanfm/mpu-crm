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
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->reference('id')->on('forms')->onDelete('restric');
            $table->integer('sequence')->nullable();
            $table->string('field_name');
            $table->string('field_label');
            $table->string('type')->default('input');
            $table->text('options')->nullable();
            $table->text('options')->nullable();
            $table->char('direction',1)->nullable();
            $table->boolean('required')->default(false);
            $table->boolean('in_column')->default(false);
            $table->string('rule')->nullable();
            $table->string('validate')->nullable();
            $table->boolean('grouping')->default(false);
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
        Schema::dropIfExists('form_fields');
    }
};
