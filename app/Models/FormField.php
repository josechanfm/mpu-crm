<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FormField extends Model
{
    use HasFactory;
    protected $fillable=['form_id','sequence','field_name','field_label','type','options','extra','direction','required','in_column','rule','validate','grouping','remark'];
    protected $casts=['options'=>'json','required'=>'boolean','in_column'=>'boolean'];

        protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate a unique ID using Str::random or any other method
            $model->uuid =  (string) Str::uuid();
        });
    }
}
