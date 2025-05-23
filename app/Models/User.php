<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\SetPasswordNotification;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use Notifiable;
    use LaravelPermissionToVueJS;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'username','name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        // 'profile_photo_url',
    ];

    public function sendPasswordResetNotification($token)
    {
        if ($this->password === 'need-to-set') {
            $this->notify(new SetPasswordNotification($token));
            return;
        }

        $this->notify(new ResetPasswordNotification($token));
    }

    public function hasPasswordSet () {
        return $this->password !== 'need-to-set';
    }

    public function member(){
        return $this->hasOne(Member::class);
    }
    // public function applications(){
    //     return $this->hasMany(User::class);
    // }
    // public function entries(){
    //     return $this->hasMany(User::class);
    // }

}
