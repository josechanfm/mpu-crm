<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecEducation extends Model
{
    use HasFactory;
    protected $fillable=['rec_application_id','school_name','region','degree','qualification','subject','language','date_start','date_finish'];

    public function application(){
        return $this->belongsTo(RecApplication::class);
    }
}
