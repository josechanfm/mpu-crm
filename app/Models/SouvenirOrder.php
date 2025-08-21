<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SouvenirOrder extends Model
{
    use HasFactory;
    
    protected $fillable = ['uuid','merc_order_no','sourvenir_user_id','form_meta','items','amount','payment_method','payment_status','status'];
    protected $casts = ['form_meta'=>'object','items'=>'array'];

    public function user(){
        return $this->belongsTo(SouvenirUser::class,'souvenir_user_id','id');
    }
    public function payment(){
        return $this->belongsTo(SouvenirPayment::class);
    }
}
