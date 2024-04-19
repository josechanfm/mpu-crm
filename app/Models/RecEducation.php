<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecEducation extends Model
{
    use HasFactory;
    protected $fillable=['rec_application_id','school_name','level','degree','subejct','lang','date_start','date_end'];

    public function application(){
        return $this->belongsTo(RecApplication::class);
    }
}
