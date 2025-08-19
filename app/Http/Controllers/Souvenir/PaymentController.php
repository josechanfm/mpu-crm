<?php

namespace App\Http\Controllers\Souvenir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\SouvenirUser;
use App\Models\SouvenirOrder;
use Inertia\Inertia;

class PaymentController extends Controller
{
    //
    public function toPay(SouvenirUser $souvenirUser, Request $request){

        dd($souvenirUser, $request->all());
        $orders= $souvenirUser->orders()->where("status", 2)->get();
        $sumAmount=$orders->sum("amount");
        

        $systemCode=env('BOC_SYSTEM_CODE','DAESP');
        $mercOrderNo=$souvenirUser->id.'-'.time().'-'.rand(1000,9999);
        $salt=env('DAESP_SALT','8Ier5T)1up]_S7)XHd(KcHwtM><cuF415P$=Dqb6}OtN_[bd');

        $payment=[
            'system_code'=>$systemCode, //Required 授權後獲得
            'ipAddress'=>$request->getClientIp(), 
            'cashier_language'=>'zh_TW', //Required zh_TW或en_US
            'amount'=>$sumAmount, //Required 交易金額(折後，如無折扣，則和originalAmount一樣即可)
            'original_amount'=>$sumAmount, //Required 交易原金額
            'subject'=>'Admin', //Required 交易標題
            'product_desc'=>'', //Optional 交易描述
            'merc_order_no'=>$mercOrderNo, //Required 訂單唯一編號
            'requester'=>'venus.mpu.edu.mo', //Optional 支付者名稱
            'order_date'=>date("Y-m-d"), //Optional 請求日期。如不填，則自動填寫系統即時日期
            'order_dime'=>date("H:i:s"), //Optional 請求時間。如不填，則自動填寫系統即時時間
            'valid_number'=>'', //Optional 交易有效時間(單位:秒)，預設為300秒
            'other_business_type'=>'Souvenir', //Required 交易類型
            'payment_channel'=>'boc', //boc, cep 當有多於一個交易渠道，使用|間隔，例如boc|cep，如不填或格式錯誤，則顯示所有可用交易渠道
            'mcs_sync_order_no'=>$souvenirUser->id, //
            'email'=>'tester@mpu.edu.mo', //交易成功後同步MCS(如有)，搜尋並更新相同orderNo的MCS記錄。
            //注1: 當你填寫了mcsSyncOrderNo，則不需要另外通過MCS paybill API做交易動作。
            //注2: MCS的交易金額不會在此步驟中發生變化，若交易時與MCS記錄創建時的金額不相同，請及時通過MCS API更新記錄。
            'sign_text'=>hash('sha256',$systemCode.$mercOrderNo.$sumAmount.$salt)
            //System Code + mercOrderNo + amount + Salt使用SHA256生成的不可逆的字串。用於識別是否為經授權的系統發出的交易。
            //(2023-08-31 更新singText中加入amount以作檢查金額沒有被惡意修改)
        ];
        $orders->update([
            'form_data'=>json_encode($request->all()),
            'transactionmeta'=>json_encode($payment)
        ]);

  
    }
    public function notify(Request $request){
        $systemCode=strtolower(env('BOC_SOUVENIR_CODE','DAESP'));
        $merchanOrderNo=substr(str_replace($systemCode,'',$request->merchanOrderNo),0,-1);
        $order=SouvenirOrder::where('merc_order_no',$merchanOrderNo)->first();
        $order->payment_notify=$request->all();
        $order->payment_status=$request->status;
        $order->status=$request->status;
        $order->save();
        return true;
    }

    public function result(Request $request){
        if (count($request->all()) == 0) {
            return Inertia::render('Souvenir/PaymentFinished',[
                    'order'=>SouvenirOrder::latest()->first()
                ]);
        }

        $systemCode=strtolower(env('BOC_SOUVENIR_CODE','DAESP'));
        $merchanOrderNo=substr(str_replace($systemCode,'',$request->merchanOrderNo),0,-1);
        $order=SouvenirOrder::where('merc_order_no',$merchanOrderNo)->first();
        $order->payment_result=$request->all();
        $order->payment_status=$request->responseStatus;
        $order->status=$request->status;
        $order->save();
        return Inertia::render('Souvenir/PaymentFinished',[
            'order'=>$order
        ]);
    }

}
