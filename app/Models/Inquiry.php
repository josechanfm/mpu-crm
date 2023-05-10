<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;
    public function parent() {
        return $this->belongsToOne(static::class, 'parent_id');
    }
    //each category might have multiple children
    public function children() {
        return $this->hasMany(static::class, 'parent_id')->with('children')->with('adminUser')->with('emails')->orderBy('id', 'asc');
    }
    public function adminUser(){
        return $this->belongsTo(AdminUser::class);
    }
    public function emails(){
        return $this->hasMany(Email::class);
    }
}
