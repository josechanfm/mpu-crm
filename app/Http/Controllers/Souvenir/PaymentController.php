<?php

namespace App\Http\Controllers\Souvenir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\SouvenirUser;
use App\Models\SouvenirOrder;
use App\Models\SouvenirPayment;
use Inertia\Inertia;

class PaymentController extends Controller
{
    private $paymentGateway;

    public function __construct()
    {
        $this->paymentGateway = 'https://epay.mpu.edu.mo/bocpaytest/ipm/cashier';
    }
    
    //
    public function confirm(Request $request){
        //dd($request->all());
        $request->validate([
            'order_uuid'=>'required'
        ]);
        $order=SouvenirOrder::where('uuid',$request->order_uuid)->first();
        $paymentData=$this->getPaymentData(session('souvenirUser'),$order, $request->getClientIp());
        return Inertia::render('Souvenir/OrderConfirmed', [
            'paymentData' => $paymentData,
            'paymentUrl' => 'https://epay.mpu.edu.mo/bocpaytest/ipm/cashier',
        ]);
        //dd($order, $request->all(), $request->getClientIp(), $this->paymentGateway,$paymentData);


   

    }
    private function getPaymentData(SouvenirUser $souvenirUser, $order, $clientIp){
        $systemCode=env('BOC_SOUVENIR_CODE','DAESP');
        
        $mercOrderNo=$order->id.'-'.time().'-'.rand(1000,9999);
        // $order->merc_order_no=$mercOrderNo;
        // $order->save();
        //$mercOrderNo=(string)$order->uuid;
        $salt=env('DAESP_SALT','jdNk7Dzs45LbMXHCkzsa00D608vr3yCJcxvrHnAcyP5JQwxL');

        $payment=[
            'systemCode'=>$systemCode, //Required 授權後獲得
            'ipAddress'=>$clientIp, 
            'cashierLanguage'=>'zh_TW', //Required zh_TW或en_US
            'amount'=>$order->amount, //Required 交易金額(折後，如無折扣，則和originalAmount一樣即可)
            'originalAmount'=>$order->amount, //Required 交易原金額
            'subject'=>'Admin', //Required 交易標題
            'productDesc'=>'', //Optional 交易描述
            'mercOrderNo'=>$mercOrderNo, //Required 訂單唯一編號
            'requester'=>'venus.mpu.edu.mo', //Optional 支付者名稱
            'orderDate'=>'',//date("Y-m-d"), //Optional 請求日期。如不填，則自動填寫系統即時日期
            'orderDime'=>'',//date("H:i:s"), //Optional 請求時間。如不填，則自動填寫系統即時時間
            'validNumber'=>'', //Optional 交易有效時間(單位:秒)，預設為300秒
            'otherBusinessType'=>'Souvenir', //Required 交易類型
            'paymentChannel'=>'boc', //boc, cep 當有多於一個交易渠道，使用|間隔，例如boc|cep，如不填或格式錯誤，則顯示所有可用交易渠道
            'mcsSyncOrderNo'=>$order->uuid, //
            'email'=>'tester@mpu.edu.mo', //交易成功後同步MCS(如有)，搜尋並更新相同orderNo的MCS記錄。
            //注1: 當你填寫了mcsSyncOrderNo，則不需要另外通過MCS paybill API做交易動作。
            //注2: MCS的交易金額不會在此步驟中發生變化，若交易時與MCS記錄創建時的金額不相同，請及時通過MCS API更新記錄。
            'signText'=>hash('sha256',$systemCode.$mercOrderNo.$order->amount.$salt)
            //System Code + mercOrderNo + amount + Salt使用SHA256生成的不可逆的字串。用於識別是否為經授權的系統發出的交易。
            //(2023-08-31 更新singText中加入amount以作檢查金額沒有被惡意修改)
        ];
        SouvenirPayment::create([
            'type'=>'send',
            'meta_data'=>$payment,
            'status'=>'send',
        ]);
        return $payment;

    }

    public function notify(Request $request){
        $payment=SouvenirPayment::create([
            'type'=>'notify1',
        ]);
        $payment=SouvenirPayment::create([
            'type'=>'notify',
            'meta_data'=>$request->all(),
            'status'=>$request->status
        ]);
        $systemCode=strtolower(env('BOC_SOUVENIR_CODE','DAESP'));
        $mercOrderNo=substr(str_replace($systemCode,'',$request->merchantOrderNo),0,-2);
        $parts=explode('-',$mercOrderNo);
        $order=SouvenirOrder::find($parts[0]);
        // $order->payment_notify=$request->all();
        $order->payment_status=$request->status;
        $order->status=$request->status=='SUCCESS'?3:2;
        $order->save();
        return true;
    }

    public function result(Request $request){
        // dd($request->all());
        // $test='daesp18-1756268443-379201';
        // $systemCode=strtolower(env('BOC_SOUVENIR_CODE','DAESP'));
        // $mercOrderNo=substr(str_replace($systemCode,'',$test),0,-2);
        // $parts=explode('-',$mercOrderNo);
        // $order=SouvenirOrder::find($parts[0]);
        // dd($test, $systemCode, $mercOrderNo, $parts[0], $order);

        $payment=SouvenirPayment::create([
            'type'=>'result',
            'meta_data'=>$request->all(),
            'status'=>$request->responseStatus
        ]);
        if (count($request->all()) == 0) {
            return Inertia::render('Souvenir/PaymentFinished',[
                    'order'=>SouvenirOrder::latest()->first()
                ]);
        }

        $systemCode=strtolower(env('BOC_SOUVENIR_CODE','DAESP'));
        $mercOrderNo=substr(str_replace($systemCode,'',$request->merchantOrderNo),0,-2);
        $parts=explode('-',$mercOrderNo);
        $order=SouvenirOrder::find((int)$parts[0]);
        //dd($systemCode, $mercOrderNo, $parts, (int)$parts[0], $order);
        $order->payment_status=$request->responseStatus;
        $order->status=$request->responseStatus=='SUCCESS'?3:2;
        $order->save();
        return Inertia::render('Souvenir/PaymentFinished',[
            'order'=>$order
        ]);
    }

}
