<?php

namespace App\Http\Controllers\Recruitment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\RecApplication;

class UserController extends Controller
{
    //
    public function profile(){
        if(session('masquerade')){
            $user=session('masquerade');
        }else{
            $user=auth()->user();
        }

        if(!isset($user)){
            session(['url_intended'=>'/recruitment/user/profile']);
            return to_route('login');
        }else{
            session()->forget('url_intended');
        }

        return Inertia::render('Recruitment/UserProfile',[
            'user'=>auth()->user(),
            'applications'=>RecApplication::with('vacancy')->whereBelongsTo(auth()->user())->get()
        ]);
    }

    public function login(){
        return Inertia::render('Recruitment/Login',[

        ]);
    }

    public function logout(){
        auth()->logout();
        return to_route('recruitment');
    }

}
