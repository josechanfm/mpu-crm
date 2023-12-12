<?php

namespace App\Providers;

use App\Models\Team;
use App\Policies\TeamPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Fortify::authenticateUsing(function ($request) {
            if(config('fortify.guard')=='admin_web'){
                if($request->local){
                    $adminLocal='admin_local';
                    $adminUser=AdminUser::where('username', $request->username)->first();
                    $validated=$adminUser && Hash::check($request->password,$adminUser->password);
                    config(['fortify.guard' => $adminLocal]);
                    Auth::shouldUse($adminLocal);
                    $validated = Auth::validate([
                        'username' => $request->username,
                        'password' => $request->password
                    ]);
                }else{
                    Auth::shouldUse(config('fortify.guard'));
                    config(['fortify.username' => 'username']);

                    $validated = Auth::validate([
                        //'mail' => $request->email,
                        'samaccountname' => $request->username,
                        'password' => $request->password
                    ]);
                }
            }else{
                Auth::shouldUse(config('fortify.guard'));
                $validated = Auth::validate([
                    'email' => $request->email,
                    'password' => $request->password
                ]);
            }
            
            return $validated ? Auth::getLastAttempted() : null;
        });
    }
}
