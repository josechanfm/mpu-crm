<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\RecApplication;
use App\Models\Entry;
use App\Models\Message;
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
            'applications'=>RecApplication::whereBelongsto(auth()->user())->with('vacancy')->get(),
            'entries'=>Entry::whereBelongsTo(auth()->user())->get(),
            'messages'=>Message::whereBelongsTo(auth()->user())->get()
        ]);
    }
    
}