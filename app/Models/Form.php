<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function fields(){
        return $this->hasMany(FormField::class);
    }
    public function hasChild(){
        return $this->fields()->exists();
    }
}
