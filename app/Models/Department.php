<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $fillable=['abbr','name_zh','name_pt','name_en','phone','href','default_route'];

    public function adminUsers(){
        return $this->belongsToMany(AdminUser::class);
    }
    public function enquiriesStat(){
        $enquiries=$this->hasMany(Enquiry::class)->get();
        foreach($enquiries as $id=>$enquiry){
            $enquiries[$id]->question_count=$enquiry->questionCount();
            $enquiries[$id]->response_count=$enquiry->responseCount();
            $enquiries[$id]->last_response=$enquiry->lastResponse();
        }
        return $enquiries;

    }
    public function enquiries(){
        return $this->hasMany(Enquiry::class)->with('questions')->orderBy('created_at','desc');
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
        return $this->hasManyThrough(EnquiryQuestion::class, Enquiry::class,'department_id','enquiry_id')->where('enquiry_questions.is_closed',false)->with('enquiry')->with('lastResponse')->orderBy('created_at','desc');
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
