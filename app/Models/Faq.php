<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;
    protected $fillable=['department_id','category_id','degree','subjects','question_zh','answer_zh','question_ne','answer_en','question_pt','answer_pt'];
    protected $casts = ['subjects'=>'json'];
    

    public static function getBySubjects($subjects){
        return Faq::where(function ($q) use ($subjects){
                    foreach($subjects as $s){
                        $q->orWhereJsonContains('subjects',$s);
                    }
                })->get();
        // $faqs=Faq::where(function($q) use($subjects){
        //     foreach($subjects as $s){
        //         $q->orWhereJsonContains('tags',$s);
        //     }
        // })->get();
        // return $faqs;
    }
    public function department(){
        return $this->belongsTo(Department::class);
    }
}
