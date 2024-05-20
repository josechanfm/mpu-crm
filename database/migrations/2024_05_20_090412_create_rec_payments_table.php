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
        Schema::create('rec_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rec_application_id'); 
            $table->string('system_code'); //授權後獲得
            $table->string('ip_address')->nullable();
            $table->string('cashier_language'); //zh_TW或en_US
            $table->string('amount'); //交易金額(折後，如無折扣，則和originalAmount一樣即可)
            $table->string('original_amount'); //交易原金額
            $table->string('subject'); //交易標題
            $table->string('product_desc')->nullable(); //交易描述
            $table->string('merc_order_no'); //訂單唯一編號
            $table->string('requester')->nullable(); //支付者名稱
            $table->string('order_date')->nullable(); //請求日期。如不填，則自動填寫系統即時日期
            $table->string('order_time')->nullable(); //請求時間。如不填，則自動填寫系統即時時間
            $table->string('valid_number')->nullable(); //交易有效時間(單位:秒)，預設為300秒
            $table->string('other_business_type'); //交易類型
            $table->string('payment_channel'); //boc, cep 當有多於一個交易渠道，使用|間隔，例如boc|cep，如不填或格式錯誤，則顯示所有可用交易渠道。
            $table->string('mcs_sync_order_no')->nullable(); //交易成功後同步MCS(如有)，搜尋並更新相同orderNo的MCS記錄。
            //注1: 當你填寫了mcsSyncOrderNo，則不需要另外通過MCS paybill API做交易動作。
            //注2: MCS的交易金額不會在此步驟中發生變化，若交易時與MCS記錄創建時的金額不相同，請及時通過MCS API更新記錄。
            $table->string('email')->nullable();
            $table->string('sign_text'); //System Code + mercOrderNo + amount + Salt使用SHA256生成的不可逆的字串。用於識別是否為經授權的系統發出的交易。
                                        //(2023-08-31 更新singText中加入amount以作檢查金額沒有被惡意修改)
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
        Schema::dropIfExists('rec_payments');
    }
};
