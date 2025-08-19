<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SouvenirOrder extends Model
{
    use HasFactory;
    
    protected $fillable = ['uuid','sourvenir_user_id','form_meta','items','amount','payment_method','payment_meta','payment_notify','payment_result','payment_status','status'];
    protected $casts = ['form_meta'=>'object','payment_notifiy'=>'object','payment_result'=>'object','items'=>'array'];

    public function user(){
        return $this->belongsTo(SouvenirUser::class,'souvenir_user_id','id');
    }
    public function payment(){
        return $this->belongsTo(SouvenirPayment::class);
    }
}
