// database/migrations/xxxx_xx_xx_create_task_files_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('task_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->string('filename');
            $table->string('original_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('file_type')->default('image'); // image, document, etc.
            $table->boolean('is_primary')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            // Index for faster queries
            $table->index(['task_id', 'file_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_files');
    }
};