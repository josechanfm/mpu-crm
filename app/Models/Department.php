<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    public function adminUsers(){
        return $this->belongsToMany(AdminUser::class);
    }
    public function enquiries(){
        return $this->hasMany(Enquiry::class)->with('questions');
    }
    public function faqs(){
        return $this->hasMany(Faq::class);
    }
    public function forms(){
        return $this->hasMany(Form::class)->with('media');
    }

    public function enquiryQuestions(){
        return $this->hasManyThrough(EnquiryQuestion::class, Enquiry::class)->with('enquiry');
    }
    public function enquiryQuestionsOpen(){
        return $this->hasManyThrough(EnquiryQuestion::class, Enquiry::class,'department_id','enquiry_id')->where('enquiry_questions.is_closed',0)->with('enquiry')->with('lastResponse');
    }

    public function members(){
        return $this->belongsToMany(Member::class);
    }
    public function hasUser($user){
        return in_array($user->id,$this->adminUsers()->get()->pluck('id')->toArray());
    }
    public function hasRight(){
        return in_array(auth()->user()->id,$this->adminUsers()->get()->pluck('id')->toArray());
    }
}
