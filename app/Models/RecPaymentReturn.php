<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecPaymentReturn extends Model
{
    use HasFactory;
    protected $fillable=[
        'rec_payment_id',
        'response_status',
        'merchant_order_no',
        'log_no',
        'actual_payAmount',
        'pay_type',
        'payment_date',
        'payment_time',
        'status'
    ];

    public static function boot(){
        parent::boot();
        static::creating(function ($model){
            $mercOrderNo=str_replace(env('BOC_SYSTEM_CODE'), '', $model->merchant_order_no);
            $mercOrderNo=substr($mercOrderNo,0,-2);
            $recPayment=RecPayment::where('merc_order_no',$mercOrderNo)->first();
            $model->merc_order_no=$mercOrderNo;
            $model->rec_payment_id=$recPayment?$recPayment->id:0;
        });
    }


}
