<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecProfessional extends Model
{
    use HasFactory;
    protected $fillable=['rec_application_id','organization_name','region','qualification','area','date_valid','date_expire','hours'];
    
    public function application(){
        return $this->belongsTo(RecApplication::class);
    }
}
