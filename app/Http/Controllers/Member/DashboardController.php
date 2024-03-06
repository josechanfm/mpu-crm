<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        dd(auth()->user());
        return Inertia::render('Member/Dashboard',[
            
        ]);
        $member=auth()->user()->member;
        if(!$member){
            return Inertia::render('Error',[
                'message'=>"You don't have membership record!"
            ]);
        };
        return Inertia::render('Member/Dashboard',[
            'member'=>$member
        ]);
    }
    
}