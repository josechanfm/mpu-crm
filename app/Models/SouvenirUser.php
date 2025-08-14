<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SouvenirUser extends Model
{
    use HasFactory;

    public function purchases(){
        return $this->hasMany(SouvenirPurchase::class);
    }
}
