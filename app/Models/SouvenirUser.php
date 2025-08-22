<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SouvenirUser extends Model
{
    use HasFactory;

    protected $fillable = ['netid', 'email', 'phone', 'faculty_code', 'degree_code', 'can_buy', 'remark'];

    public function orders(){
        return $this->hasMany(SouvenirOrder::class);
    }
    public function payments(){
        return $this->hasMany(SouvenirPayment::class);
    }

}
