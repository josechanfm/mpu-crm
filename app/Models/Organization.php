<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AdminUser;

class Organization extends Model
{
    use HasFactory;

    public function adminUsers(){
        return $this->belongsToMany(AdminUser::class);
    }

    public function members(){
        return $this->belongsToMany(Member::class);
    }

    public function hasUser($user){
        return in_array($user->id,$this->adminUsers()->get()->pluck('id')->toArray());
    }

    public function certificates(){
        return $this->hasMany(Certificate::class);
    }
    public function forms(){
        return $this->hasMany(Form::class);
    }

}
