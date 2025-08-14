<?php

namespace App\Http\Controllers\Souvenir;

use App\Http\Controllers\Controller;
use App\Models\Souvenir;
use App\Models\SouvenirUser;
use App\Models\SouvenirPurchase;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return Inertia::render("Souvenir/Purchase",[
            "user"=>session("souvenirUser")?->load('purchases'),
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
        //  dd($request->all(), $request->cartItems, session('souvenirUser'));
        return to_route("souvenir.checkoutOrder");
    }
    public function checkoutOrder(Request $request){

        //  dd($request->all(), $request->cartItems, session('souvenirUser'));
        //if session('souvenirUser) not exist return to sourvenir home page.
        // dd(session('cart'));
        $cart=session('cart');
        
        if($cart==null){
            return redirect()->route('souvenir');
        }
        $cart['uuid']=Str::uuid();
        $cart['client_ip']=$request->getClientIp();
        $purchase=$this->storeToPurchase(session('souvenirUser'), $cart);
        $paymentData=$this->getPaymentData(session('souvenirUser'),$purchase, $cart['client_ip']);
        $purchase->payment_meta=json_encode($paymentData);
        $purchase->save();
        return Inertia::render("Souvenir/Checkout",[
            "user"=>session("")?->load(""),
            "cart"=>$cart,
            "payment"=>$paymentData,
        ]);
    }
    private function storeToPurchase($souvenirUser, $params){
        // dd($request->all(),session('souvenirUser'));
        // $params=$request->all();
        // //$souvenirUser=SouvenirUser::where("netid",   $params["netid"])->first();
        // $souvenirUser=session('souvenirUser');
        $purchaseItems=[];
        $totalAmount=0;
        foreach($params['cartItems'] as $item){
            $souvenir=Souvenir::find($item['id']);
            $purchaseItems[]=[
                'souvenir_id'=> $souvenir->id,
                'name'=> $souvenir->name,
                'qty'=>$item['count'],
                'price'=>$souvenir->price,
                'amount'=>$souvenir->price*$item['count'],
            ];
            $totalAmount+=$souvenir->price*$item['count'];
        }
        try {
            $purchase=$souvenirUser->purchases()->firstOrCreate([
                'uuid'=>$params['uuid'],
                'form_meta'=>json_encode($params),
                'items'=>$purchaseItems,
                'amount'=>$totalAmount,
                'payment_method'=>'ONLINE'
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('souvenir');
            // Handle the exception (e.g., log it, return a message, etc.)
            // You can check for specific error codes if needed
        }
        return $purchase;
    }
    private function getPaymentData(SouvenirUser $souvenirUser, $purchase, $clientIp){
        $systemCode=env('BOC_SYSTEM_CODE','MPUCRM');
        $mercOrderNo=$souvenirUser->id.'-'.time().'-'.rand(1000,9999);
        $amount=$purchase->totalAmount;
        $salt=env('BOC_SALT','8Ier5T)1up]_S7)XHd(KcHwtM><cuF415P$=Dqb6}OtN_[bd');

        $payment=[
            'system_code'=>$systemCode, //Required 授權後獲得
            'ipAddress'=>$clientIp, 
            'cashier_language'=>'zh_TW', //Required zh_TW或en_US
            'amount'=>$amount, //Required 交易金額(折後，如無折扣，則和originalAmount一樣即可)
            'original_amount'=>$amount, //Required 交易原金額
            'subject'=>'Admin', //Required 交易標題
            'product_desc'=>'', //Optional 交易描述
            'merc_order_no'=>$mercOrderNo, //Required 訂單唯一編號
            'requester'=>'venus.mpu.edu.mo', //Optional 支付者名稱
            'order_date'=>date("Y-m-d"), //Optional 請求日期。如不填，則自動填寫系統即時日期
            'order_dime'=>date("H:i:s"), //Optional 請求時間。如不填，則自動填寫系統即時時間
            'valid_number'=>'', //Optional 交易有效時間(單位:秒)，預設為300秒
            'other_business_type'=>'Souvenir', //Required 交易類型
            'payment_channel'=>'boc', //boc, cep 當有多於一個交易渠道，使用|間隔，例如boc|cep，如不填或格式錯誤，則顯示所有可用交易渠道
            'mcs_sync_order_no'=>$purchase->uuid, //
            'email'=>'tester@mpu.edu.mo', //交易成功後同步MCS(如有)，搜尋並更新相同orderNo的MCS記錄。
            //注1: 當你填寫了mcsSyncOrderNo，則不需要另外通過MCS paybill API做交易動作。
            //注2: MCS的交易金額不會在此步驟中發生變化，若交易時與MCS記錄創建時的金額不相同，請及時通過MCS API更新記錄。
            'sign_text'=>hash('sha256',$systemCode.$mercOrderNo.$amount.$salt)
            //System Code + mercOrderNo + amount + Salt使用SHA256生成的不可逆的字串。用於識別是否為經授權的系統發出的交易。
            //(2023-08-31 更新singText中加入amount以作檢查金額沒有被惡意修改)
        ];
        // $data=[];
        // foreach($payment as $key=>$value){
        //     $data[Str::snake($key)]=$value;
        // };
        //$data['rec_application_id']=$application->id;
        // RecPayment::create($data);

        return $payment;
    }
}
