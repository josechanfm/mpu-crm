<?php

namespace App\Providers;

use App\Models\Team;
use App\Policies\TeamPolicy;
use App\Policies\EnquiryQuestionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\EnquiryQuestion;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
        EnquiryQuestion::class=>EnquiryQuestionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // Fortify::authenticateUsing(function ($request) {
        //     //dd($request->local,config('fortify.guard'),config('fortify.username'),$request->all());
        //     // dd(config('fortify.guard'));
        //     // dd(config('fortify.username'));
        //     // dd($request->all());
        //     if(config('fortify.guard')=='admin' || config('fortify.guard')=='admin_local'){
        //         if($request->local){
        //             config(['fortify.username' => 'username']);
        //             config(['fortify.guard' => 'admin_local']);
        //             Auth::shouldUse(config('fortify.guard'));
        //             $validated = Auth::validate([
        //                 'username' => $request->username,
        //                 'password' => $request->password
        //             ]);
        //         }else{
        //                 config(['fortify.username' => 'username']);
        //                 config(['fortify.guard' => 'admin']);
        //                 Auth::shouldUse(config('fortify.guard'));
        //                 $validated = Auth::validate([
        //                     //'mail' => $request->username,
        //                     'samaccountname' => $request->username,
        //                     'password' => $request->password
        //                 ]);
        //         }
        //     }else{
        //         config(['fortify.username' => 'email']);
        //         config(['fortify.guard' => 'web']);
        //         Auth::shouldUse(config('fortify.guard'));
        //         $validated = Auth::validate([
        //             'email' => $request->email,
        //             'password' => $request->password
        //         ]);
        //     }
        //     return $validated ? Auth::getLastAttempted() : null;
        // });
    }
}
