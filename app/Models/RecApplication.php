<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecApplication extends Model
{
    use HasFactory;
    protected $fillable=['info_received','name_family_zh','name_given_zh','name_family_fn','name_given_fn','gender','pob','pob_oth','dob','id_type','id_num','id_issue',
    'nationality','nationality_oth','language','address','tel','email','supplement','status','submitted','admin_id','payment','quick_pay'];
 
    public function vacancy(){
        return $this->belongsTo(RecVacancy::class);
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

}
