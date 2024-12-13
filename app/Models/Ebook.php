<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
    use HasFactory;
    protected $fillable=['uid','original_filename','title','description','date_start','date_end','remark'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ebook) {
            // Generate a unique ID using Str::random or any other method
            $ebook->uid = $ebook->_base62_encode((int)(microtime(true)*1000));
        });
    }
    public function _base62_encode($num){
        $alphabet = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $base = strlen($alphabet);
        $encoded = '';
    
        while ($num > 0) {
            $encoded = $alphabet[$num % $base] . $encoded;
            $num = floor($num / $base);
        }
    
        return $encoded;   
    }

}
