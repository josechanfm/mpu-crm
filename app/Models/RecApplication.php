<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecApplication extends Model
{
    use HasFactory;
    protected $fillable=['user_id','rec_vacancy_id','obtain_info','name_zh','first_name_fn','last_name_fn','gender','pob','pob_oth','dob','id_type','id_type_name','id_num','id_issue',
    'nationality','nationality_oth','language','address','phone','email','supplement','status','submitted','admin_id','payment','quick_pay'];
    protected $casts=['obtain_info'=>'array','submitted'=>'boolean'];

    public function vacancy(){
        return $this->belongsTo(RecVacancy::class,'rec_vacancy_id');
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
    public function user(){
        return $this->belongsTo(User::class);
    }

}
