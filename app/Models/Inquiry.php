<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Inquiry extends Model
{
    use HasFactory;
    protected $fillable=['email','name','token'];

    public function department(){
        return $this->belongsTo(Department::class);
    }
    public function parent() {
        return $this->belongsToOne(static::class, 'parent_id');
    }
    //each category might have multiple children
    public function children() {
        //return $this->hasMany(static::class, 'parent_id')->with('children')->with('adminUser')->with('emails')->orderBy('id', 'asc');
        return $this->hasMany(static::class, 'parent_id')->with('children')->with('adminUser')->with('emails')->orderBy('id', 'asc');
    }
    public function adminUser(){
        return $this->belongsTo(AdminUser::class);
    }
    public function emails():MorphMany{
        return $this->morphMany(Email::class,'emailable')->with('media');
    }
    // public function emails(){
    //     return $this->hasMany(Email::class);
    // }
}
