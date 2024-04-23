<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;
    protected $casts=['value'=>'json'];

    public static function item($key){
        return Config::where('key',$key)->first();
    }
    public static function enquiryFormFields(){
        $items=Config::where('key','enquiry_form')->get()->groupBy('lang');
        $fields=[];
        forEach($items as $lang=>$group){
            $fields[$lang]=[];
            forEach($group as $g){
                $fields[$lang][$g->label]=$g->value;
            }
            // $tmp=array_column($group->toArray(),null,'label');
            // $fields[$lang]=[];
            // forEach($tmp as $key=>$t){
            //     dd($key);
            //     $fields[$lang][]=$t['value'];
            // }
        }
        
        // $fields=Config::where('key','enquiry_form')->get()->toArray();
        // dd(array_column($fields,null,'lang'));
        // $fields=array_column($fields,null,'label');
        // $fields=array_map(function($field){
        //      return $field['value'];
        // },$fields);
        return $fields;
    }
    public static function enquirySubjects(){
        $item=Config::where('key','enquiry_form')->where('label','subjects')->first();
        return json_decode($item->value)->options;
    }
}
