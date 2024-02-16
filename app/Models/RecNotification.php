<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecNotification extends Model
{
    use HasFactory;
    protected $fillable=['rec_vacancy_id','title_zh','title_en','title_pt','date_start','date_end','file_zh','file_en','file_pt','can_donwload','can_apply','published'];
    protected $casts=['can_download'=>'boolean','can_apply'=>'boolean','published'=>'boolean'];

    public function vacancy(){
        return $this->belongsTo(RecVacancy::class,'rec_vacancy_id');
    }
}
