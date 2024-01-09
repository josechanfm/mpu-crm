<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;
    protected $casts=['value'=>'json'];

    public static function item($division){
        return Config::where('division',$division)->first();
    }
    public static function enquiryFormFields(){
        $fields=Config::where('division','enquiry_form')->get()->toArray();
        $fields=array_column($fields,null,'label');
        $fields=array_map(function($field){
             return $field['value'];
        },$fields);
        return $fields;
    }
    public static function enquirySubjects(){
        $item=Config::where('division','enquiry_form')->where('label','subjects')->first();
        return json_decode($item->value)->options;
    }
}
