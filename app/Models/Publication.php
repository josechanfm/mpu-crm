<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use HasFactory;
    protected $fillable=['department_id','category','title_zh','title_pt','title_en','description_zh','description_pt','description_en','author','print','cover'];
    protected $appends=['cover_url'];

    public function getCoverUrlAttribute(){
        return asset('media/'.$this->cover);
    }
    public function department(){
        return $this->belongsTo(Department::class);
    }
}
