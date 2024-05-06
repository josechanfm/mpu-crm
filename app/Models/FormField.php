<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    use HasFactory;
    protected $fillable=['form_id','sequence','field_name','field_label','type','options','extra','direction','required','in_column','rule','validate','remark'];
    protected $casts=['options'=>'json'];
}
