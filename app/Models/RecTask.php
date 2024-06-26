<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecTask extends Model
{
    use HasFactory;
    protected $fillable=['vacancy_type','sequence','name','department_id','days','email','remark'];

    public function department(){
        return $this->belongsto(Department::class);
    }
}
