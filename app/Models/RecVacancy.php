<?php
namespace App\Models;
    
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecVacancy extends Model
{
    use HasFactory;
    protected $fillable=['type','code','title_zh','title_en','title_pt','education','vehicle','apply_id','apply_title','description','date_start','date_end',
                        'supplement_start','supplement_end','date_publish','attach_zh','attach_en','attach_pt','progress','active','fee'];
    protected $casts=['progress'=>'boolean','active'=>'boolean'];

    public function applications(){
        return $this->hasMany(RecApplication::class);
    }
    public function notices(){
        return $this->hasMany(RecNotice::class);
    }
}
