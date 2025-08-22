<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Souvenir extends Model
{
    use HasFactory;

    protected $fillable = ['name','description','thumbnail','stock','is_available','price','quota','thumbnail','images','remark'];
    protected $casts = ['images'=>'array','is_available'=>'boolean'];
}
