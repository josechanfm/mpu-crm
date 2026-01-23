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
        Schema::create('staff_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id'); // Foreign key to staff
            $table->string('collection_name', 50);
            $table->string('reference_table', 255);
            $table->string('reference_id', 255);
            $table->string('file_name', 255);
            $table->string('path', 255);
            $table->text('meta');
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
        Schema::dropIfExists('staff_uploads');
    }
};
