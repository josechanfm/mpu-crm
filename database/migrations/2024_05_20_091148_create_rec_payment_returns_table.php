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
        Schema::create('rec_payment_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rec_payment_id')->nullable();
            $table->string('merc_order_no'); 
            $table->string('response_status')->nullable(); //SUCCESS|FAIL|notify
            $table->string('merchant_order_no'); //訂單號碼(systemCode + mercOrderNo + XX) XX為兩位數字，由01 – 99，代表該訂單編號提交
            $table->string('log_no')->nullable(); //渠道方唯一查詢號碼
            $table->string('actual_payAmount')->nullable(); //實際支付金額
            $table->string('pay_type')->nullable(); //交易類型 BOC
            $table->string('payment_date')->nullable(); //交易日期，格式: yyyy-MM-dd
            $table->string('payment_time')->nullable(); //交易時間，格式:HH:mm:ss
            $table->string('status')->nullable();  //SUCCESS
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
        Schema::dropIfExists('rec_payment_returns');
    }
};
