<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enquiry extends Model
{
    use HasFactory;

    protected $fillable=['department_id','lang','origin','degree','admission','profile','apply','surname','givenname','email','areacode','phone','subjects','has_question','token','is_closed'];
    protected $casts=['subjects'=>'array'];

    protected static function boot(){
        parent::boot();
        static::creating(function ($model){
            $model->token=hash('crc32',time().'mpu-crm');
        });
        // static::retrieved(function ($model){
        //     $d=date('Y-m-d', strtotime($model->created_at. ' + 2 day'));
        //     $model->escalated=now()>$d;
        //     $model->save();
        // });
    }

    public function department(){
        return $this->belongsTo(Department::class);
    }
    public function adminUser(){
        return $this->belongsTo(AdminUser::class);
    }
    public function questions(){
        return $this->hasMany(EnquiryQuestion::class)->with('responses')->with('media')->orderBy('created_at','desc');
    }
    public function questionCount(){
        return $this->hasMany(EnquiryQuestion::class)->count();
    }
    public function responseCount(){
        return $this->hasManyThrough(EnquiryResponse::class,EnquiryQuestion::class)->count();
    }
    public function lastResponse(){
        return $this->throughQuestions()->hasResponses()->orderBy('created_at','desc')->first();
        //return $this->hasOneThrough(EnquiryResponse::class,EnquiryQuestion::class)->get();
    }
    // public static function token(){
    //     return hash('crc32',time().'mpu-crm');
    // }

}
