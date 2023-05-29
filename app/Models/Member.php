<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Dpartment;

class Member extends Model
{
    use HasFactory;

    public function createUser(): User
    {
        $user = new User();

        $user->email = $this->email;
        $user->name = $this->first_name;
        $user->password = 'need-to-set';

        $user->save();

        return $user;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hasUser()
    {
        return $this->user()->exists();
    }

    public function departments(){
        return $this->belongsToMany(Dpartment::class);
    }

    public function belongsToDpartment($department){
        return $this->belongsToMany(Dpartment::class)->wherePivot('department_id', $department->id);
    }

    public function certificates(){
        return $this->belongsToMany(Certificate::class)->withPivot(
            'id','display_name','number','number_display','issue_date','valid_from','valid_until','authorize_by','rank','avata');
    }
}
