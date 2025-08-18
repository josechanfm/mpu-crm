<?php

namespace App\Http\Controllers\Souvenir;

use App\Http\Controllers\Controller;
use App\Models\RecApplication;
use App\Models\RecPayment;
use App\Models\RecPaymentReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    //

    public function notify(Request $request){
        dd($request->all());
        $data=[];
        foreach($request->all() as $key=>$value){
            $data[Str::snake($key)]=$value;
        };
        $paymentReturn=RecPaymentReturn::create($data);
        if(!empty($paymentReturn) && $paymentReturn->status=='SUCCESS'){
            $payment=RecPayment::find($paymentReturn->rec_payment_id);
            if(!empty($payment)){
                $application=RecApplication::find($payment->rec_application_id);
                if(empty($application)){
                    return 'Error! Invalide operation.';
                }
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
        dd($request->all());
        $data=[];
        foreach($request->all() as $key=>$value){
            $data[Str::snake($key)]=$value;
        };
        RecPaymentReturn::create($data);
        return true;
    }

}
