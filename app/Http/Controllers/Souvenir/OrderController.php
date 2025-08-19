<?php

namespace App\Http\Controllers\Souvenir;

use App\Http\Controllers\Controller;
use App\Models\Souvenir;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http; 
use App\Models\SouvenirUser;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render("Souvenir/Order",[
            "user"=>session("souvenirUser")?->load('orders'),
            "products"=>Souvenir::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function checkout(Request $request){
        session()->flash('cart',$request->all());
        return to_route("souvenir.checkoutOrder");
    }
    public function checkoutOrder(Request $request){
        $cart=session('cart');

        if($cart==null){
            return redirect()->route('souvenir');
        }
        $cart['uuid']=Str::uuid();
        $cart['client_ip']=$request->getClientIp();
        $order=$this->storeToOrder(session('souvenirUser'), $cart);

        $paymentData=$this->getPaymentData(session('souvenirUser'),$order, $cart['client_ip']);
        $order->payment_meta=json_encode($paymentData);
        $order->save();
        return Inertia::render("Souvenir/Checkout",[
            "user"=>session("")?->load(""),
            "cart"=>$cart,
            "payment"=>$paymentData,
        ]);
    }
    
    private function storeToOrder($souvenirUser, $params){
        
        $orderItems=[];
        $totalAmount=0;
        foreach($params['cartItems'] as $item){
            $souvenir=Souvenir::find($item['id']);
            $orderItems[]=[
                'souvenir_id'=> $souvenir->id,
                'name'=> $souvenir->name,
                'qty'=>$item['count'],
                'price'=>$souvenir->price,
                'amount'=>$souvenir->price*$item['count'],
            ];
            $totalAmount+=$souvenir->price*$item['count'];
        }
        try {
            $order=$souvenirUser->orders()->firstOrCreate([
                'uuid'=>Str::uuid(),
                'form_meta'=>$params,
                'items'=>$orderItems,
                'amount'=>$totalAmount,
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('souvenir');
            // Handle the exception (e.g., log it, return a message, etc.)
            // You can check for specific error codes if needed
        }
        return $order;
    }

    private function getPaymentData(SouvenirUser $souvenirUser, $order, $clientIp){
        $systemCode=env('BOC_SOUVENIR_CODE','DAESP');
        
        $mercOrderNo=$order->id.'-'.time().'-'.rand(1000,9999);
        $order->merc_order_no=$mercOrderNo;
        $order->save();
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

        return $payment;
    }
    public function pickupCode(){
        //dd(hash('sha256',session('souvenirUser')->id));
        $salt=env('SALT','dae-souvenir');
        
        $pickupCode=session('souvenirUser')->id.'-'.hash('sha256',session('souvenirUser')->id.$salt);
        return Inertia::render('Souvenir/PickupCode',[
            'user'=>session('souvenirUser'),
            'pickupCode'=>$pickupCode,
        ]);
    }
}
