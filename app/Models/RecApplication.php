<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RecApplication extends Model
{
    use HasFactory;
    protected $fillable=['user_id','rec_vacancy_id','obtain_info','name_full_zh','name_family_zh','name_given_zh','name_full_fn','name_family_fn','name_given_fn','gender','pob','pob_oth','dob','id_type','id_type_name','id_num','id_issue',
    'nationality','nationality_oth','language','address','phone','email','supplement','status','submitted','admin_id','payment','quick_pay'];
    protected $casts=['obtain_info'=>'array','submitted'=>'boolean'];


    public static function boot(){
        parent::boot();
        self::creating(function($model){
            $model->uuid=Str::uuid();
        });
    }

    public function vacancy(){
        return $this->belongsTo(RecVacancy::class,'rec_vacancy_id')->with('notices');
    }
    public function educations(){
        return $this->hasMany(RecEducation::class);
    }
    public function experiences(){
        return $this->hasMany(RecExperience::class);
    }
    public function professionals(){
        return $this->hasMany(RecProfessional::class);
    }
    public function uploads(){
        return $this->hasMany(RecUpload::class);
    }
    public function payments(){
        return $this->hasMany(RecPayment::class);
    }
    public function paid(){
        return $this->hasOneThrough(RecPaymentReturn::class,RecPayment::class)->where('status','SUCCESS');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

}
