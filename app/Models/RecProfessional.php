<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecProfessional extends Model
{
    use HasFactory;
    
    public function application(){
        return $this->belongsTo(RecApplication::class);
    }
}
