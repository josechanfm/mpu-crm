<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    public function organization(){
        return $this->belongsTo(Organization::class);
    }

    public function fields(){
        return $this->hasMany(FormField::class);
    }
    public function hasChild(){
        return $this->fields()->exists();
    }
}
