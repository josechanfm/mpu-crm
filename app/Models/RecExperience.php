<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecExperience extends Model
{
    use HasFactory;
    protected $fillable=['rec_application_id','company_name','region','position','salary','employment','date_join','date_leave','description'];

    public function application(){
        return $this->belongsTo(RecApplication::class);
    }
}
