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

    public function department(){
        return $this->belongsTo(Department::class);
    }
    public function adminUser(){
        return $this->belongsTo(AdminUser::class);
    }
    public function questions(){
        return $this->hasMany(EnquiryQuestion::class)->with('responses')->with('media');
    }

    // public static function token(){
    //     return hash('crc32',time().'mpu-crm');
    // }

}
