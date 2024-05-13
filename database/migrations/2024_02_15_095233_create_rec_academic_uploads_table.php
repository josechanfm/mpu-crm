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
        Schema::create('rec_academic_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rec_application_id');
            $table->string('path');
            $table->string('file_name');
            $table->string('full_path');
            $table->string('document_type'); //IDC,EDU,RSM,WRK,TRN,ACH,OTH
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
        Schema::dropIfExists('rec_academic_uploads');
    }
};
