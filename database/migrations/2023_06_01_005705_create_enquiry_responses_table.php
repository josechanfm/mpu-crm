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
        Schema::create('enquiry_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enquiry_question_id');
            $table->text('title')->nullable();
            $table->text('remark');
            $table->boolean('by_email');
            $table->string('email_address')->nullable();
            $table->string('email_subject')->nullable();
            $table->text('email_content')->nullable();
            $table->foreignId('admin_id');
            $table->string('token')->nullable();
            $table->boolean('is_used')->nullable();
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
        Schema::dropIfExists('enquiry_responses');
    }
};
