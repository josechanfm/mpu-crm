<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manual extends Model
{
    use HasFactory;
    protected $fillable=['parent_id','route','reroute','title','content'];

    public function parent(){
        return $this->belongsTo(Manual::class, 'parent_id');
    }
}
