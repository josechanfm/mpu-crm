<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shortener extends Model
{
    use HasFactory;

    protected $fillable=['token','title','link','valid_at', 'expire_at','admin_user_id','remark'];

    protected static $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

     protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate a unique random string
            $model->token = self::generateUniqueRandomString();
        });
    }

    protected static function generateRandomString($length = 5)
    {
        $charactersLength = strlen(self::$characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= self::$characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    protected static function generateUniqueRandomString($length = 5)
    {
        do {
            $randomString = self::generateRandomString($length);
        } while (self::where('token', $randomString)->exists());

        return $randomString;
    }


}
