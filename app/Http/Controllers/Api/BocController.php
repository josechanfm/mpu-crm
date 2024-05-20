<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        RecPaymentReturn::create($data);
        $mercOrderNo=str_replace(env('BOC_SYSTEM_CODE'), '', $data['merchant_order_no']);
        $mercOrderNo=substr($mercOrderNo,0,-2);
        return true;
    }

    public function result(Request $request){
        $data=[];
        foreach($request->all() as $key=>$value){
            $data[Str::snake($key)]=$value;
        };

        RecPaymentReturn::create($data);
        $mercOrderNo=str_replace(env('BOC_SYSTEM_CODE'), '', $data['merchant_order_no']);
        $mercOrderNo=substr($mercOrderNo,0,-2);
        return true;
    }

}
