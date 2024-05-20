<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RecApplication;
use App\Models\RecPayment;
use App\Models\RecPaymentReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BocController extends Controller
{
    //

    public function notify(Request $request){
        $data=[];
        foreach($request->all() as $key=>$value){
            $data[Str::snake($key)]=$value;
        };
        $paymentReturn=RecPaymentReturn::create($data);
        if(!empty($paymentReturn) && $paymentReturn->status=='SUCCESS'){
            $payment=RecPayment::find($paymentReturn->rec_payment_id);
            if(!empty($payment)){
                $application=RecApplication::find($payment->rec_application_id);
                if($application->rec_payment_id==null){
                    $application->rec_payment_id=$payment->id;
                    $application->save();
                }
                //RecApplication::find($payment->rec_application_id)->update(['rec_payment_id'=>$payment->id]);
            }
        }
        return true;
    }

    public function result(Request $request){
        $data=[];
        foreach($request->all() as $key=>$value){
            $data[Str::snake($key)]=$value;
        };
        RecPaymentReturn::create($data);
        return true;
    }

}
