<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecActivity extends Model
{
    use HasFactory;
    protected $fillable=['rec_workflow_id','name','sequence','days','department_id','email','date_start','date_end','target_start','target_end','active','remark'];
    protected $casts=['active'=>'boolean'];

    public function workflow(){
        return $this->belongsTo(RecWorkflow::class);
    }
}
