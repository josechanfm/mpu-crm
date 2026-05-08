<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Str;

class SouvenirUser extends Model implements CanResetPasswordContract
{
    use HasFactory, Notifiable, CanResetPassword;

    protected $fillable = ['notify_email', 'netid', 'name', 'email', 'password', 'phone', 'faculty_code', 'degree_code','grad_year', 'can_buy', 'token', 'notify_sent', 'remark', 'email_verification_code', 'email_verified_at', 'active','meta_data'];
    protected $hidden=['password'];
    protected $casts = [
        'can_buy'=> 'boolean',
        'email_verified_at' => 'datetime',
        'active'=>'boolean',
        'meta_data'=>'object'
    ];

    public static array $faculties = [
        [
            'label' => 'Faculty of Applied Sciences / 應用科學學院',
            'value' => 'FCA',
        ],
        [
            'label' => 'Faculty of Health Sciences and Sports / 健康科學及體育學院',
            'value' => 'FCSD',
        ],
        [
            'label' => 'Faculty of Languages and Translation / 語言及翻譯學院',
            'value' => 'FLT',
        ],
        [
            'label' => 'Faculty of Arts and Design / 藝術及設計學院',
            'value' => 'FAD',
        ],
        [
            'label' => 'Faculty of Humanities and Social Sciences / 人文及社會科學學院',
            'value' => 'FCHS',
        ],
        [
            'label' => 'Faculty of Business / 管理科學學院',
            'value' => 'FCG',
        ],
        [
            'label' => 'Peking University Health Science Center – Macao Polytechnic University Nursing Academy / 北京大學醫學部——澳門理工大學護理書院',
            'value' => 'AE',
        ],
    ];

    public static array $degrees = [
        [
            'label' => 'Bachelor / 學士',
            'value' => 'BACHALOR',
        ],
        [
            'label' => 'Master / 碩士',
            'value' => 'MASTER',
        ],
        [
            'label' => 'PhD / 博士',
            'value' => 'PHD',
        ],
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->uuid)) {
                $user->uuid = (string) Str::uuid();
            }
        });
    }

    public function orders(){
        return $this->hasMany(SouvenirOrder::class);
    }
    public function payments(){
        return $this->hasMany(SouvenirPayment::class);
    }

}
