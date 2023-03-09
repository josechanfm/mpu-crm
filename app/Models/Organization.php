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
        echo json_encode($this);
        echo '<hr>';
        echo json_encode($user);
        echo '<hr>';
        echo json_encode($this->adminUsers()->pluck('admin_user_id'));
        echo '<hr>';
        echo json_encode($this->adminUsers()->exists($user));
        dd($this->adminUsers()->pluck('admin_user_id'));


        return $this->adminUsers()->exists();
    }
}
