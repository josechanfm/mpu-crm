<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $casts=['created_at'=>'datetime:Y-m-d: H:i:s'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function admin_user(){
        return $this->belongsTo(User::class);
    }
}
