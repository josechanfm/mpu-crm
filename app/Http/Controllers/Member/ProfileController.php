<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Member;

class ProfileController extends Controller
{
    public function index(){
        return Inertia::render('Member/Profile',[
            'member'=>auth()->user()->member,
        ]);
    }
    public function update(Request $request){

    }

    public function changePassword(Request $request){

    }
}
