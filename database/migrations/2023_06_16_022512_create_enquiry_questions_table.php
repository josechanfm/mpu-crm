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
        Schema::create('enquiry_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enquiry_id');
            $table->foreignId('parent_id')->nullable();
            $table->text('content');
            $table->string('token');
            $table->boolean('is_closed')->default(0);
            $table->foreignId('admin_id')->nullable();
            $table->boolean('escalated')->nullable();
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
        Schema::dropIfExists('enquiry_questions');
    }
};
