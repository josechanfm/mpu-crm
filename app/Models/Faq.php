<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    // public static function getByTags($subjects){
    //     return Faq::where(function ($q) use ($subjects){
    //                 foreach($subjects as $s){
    //                     $q->orWhereJsonContains('subjects',$s);
    //                 }
    //             })->get();
    //     // $faqs=Faq::where(function($q) use($subjects){
    //     //     foreach($subjects as $s){
    //     //         $q->orWhereJsonContains('tags',$s);
    //     //     }
    //     // })->get();
    //     // return $faqs;
    // }
    public function department(){
        return $this->belongsTo(Department::class);
    }
}
