<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gpdp extends Model
{
    use HasFactory;
    protected $fillable=['staff_num','name_zh','name_fr','email','date_start','date_remind','date_due','is_valid','remark'];
    
}
