<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecTask extends Model
{
    use HasFactory;

    public function department(){
        return $this->belongsto(Department::class);
    }
}
