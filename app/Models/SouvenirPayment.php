<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SouvenirPayment extends Model
{
    use HasFactory;
    protected $fillable = ['uuid','sourvenir_user_id','order_ids','amount','payment_method','payment_meta','transaction_code','transaction_meta','status'];
    protected $casts = ['order_ids'=>'array'];

    public function orders(){
        return $this->hasMany(SouvenirOrder::class);
    }

}
