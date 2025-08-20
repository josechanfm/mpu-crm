<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SouvenirPayment extends Model
{
    use HasFactory;
    protected $fillable = ['type','meta_data','order_id','status'];
    protected $casts = ['meta_data'=>'array'];

    public function orders(){
        return $this->hasMany(SouvenirOrder::class);
    }

}
