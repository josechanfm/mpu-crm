<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // session()->flush();
        //dd(session('recruitment_url'));
        if(session('url_intended')){
            return redirect(session('url_intended'));
        }
        return Inertia::render('Member/Dashboard',[
            
        ]);
    }
    
}