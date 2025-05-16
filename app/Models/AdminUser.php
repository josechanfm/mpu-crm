<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use LdapRecord\Laravel\Auth\HasLdapUser;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;


class AdminUser extends Authenticatable implements LdapAuthenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use Notifiable, AuthenticatesWithLdap;
    use HasLdapUser;
    use LaravelPermissionToVueJS;



    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected $fillable = ['name', 'username','email','email_varified_at', 'password','remember_token','current_team_id','profile_photo_path','guid','domain'];
    protected $guard = 'admin';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
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
    // protected $appends = [
    //     'profile_photo_url',
    // ];


    public function departments(){
        return $this->belongsToMany(Department::class);
    }

}
