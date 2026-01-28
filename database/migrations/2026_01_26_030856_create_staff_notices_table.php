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
        Schema::create('staff_notices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_upload_id');
            $table->integer('age');
            $table->string('email');
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->date('date');
            $table->date('sent_at')->nullable();
            //$table->char('status',1)->enum(['N','S','F'])->default(null); //N=Not send, S=Sent, F=Faild
            $table->enum('status', ['N', 'S', 'F'])->default('N'); // N = Not sent, S = Sent, F = Failed
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
        Schema::dropIfExists('staff_notices');
    }
};
