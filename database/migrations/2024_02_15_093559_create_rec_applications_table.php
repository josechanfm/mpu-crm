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
        Schema::create('rec_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rec_vacancy_id');
            $table->uuid('uuid');
            $table->string('obtain_info')->nullable();
            $table->char('exam_lang',2)->nullable();
            $table->string('name_full_zh')->nullable();
            $table->string('name_family_zh')->nullable();
            $table->string('name_given_zh')->nullable();
            $table->string('name_full_en')->nullable();
            $table->string('name_family_en')->nullable();
            $table->string('name_given_en')->nullable();
            $table->char('gender',1);
            $table->char('pob',3)->nullable(); //CHN,MAC,HKG,OTH
            $table->string('pob_oth')->nullable();
            $table->date('dob');
            $table->string('id_type')->nullable(); //MAC_RST,MAC_TMP,HKG_RES,CHN_IDC,PPT_INT,OTH_DOC
            $table->string('id_type_name')->nullable(); //MAC_RST,MAC_TMP,HKG_RES,CHN_IDC,PPT_INT,OTH_DOC
            $table->string('id_num');
            $table->string('id_issue')->nullable();
            $table->string('nationality');  //CHN,PPT,OTH
            $table->string('nationality_oth')->nullable();
            $table->text('language')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('supplement')->nullable();
            $table->string('status')->nullable();
            $table->date('submit_at')->nullable();
            $table->string('submitted')->nullable();
            $table->foreignId('admin_id')->nullable();
            $table->string('payment')->nullable();
            $table->string('quick_pay')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('payment_id')->nullable();
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
        Schema::dropIfExists('rec_applications');
    }
};
