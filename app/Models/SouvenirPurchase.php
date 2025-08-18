<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SouvenirPurchase extends Model
{
    use HasFactory;
    
    protected $fillable = ['uuid','sourvenir_user_id','form_meta','items','amount','payment_method','payment_meta','transaction_code','transaction_meta','status'];
    protected $casts = ['form_meta'=>'object','items'=>'array'];

    public function user(){
        return $this->belongsTo(SouvenirUser::class,'souvenir_user_id','id');
    }
}
