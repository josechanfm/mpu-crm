<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MembershipController extends Controller
{
    public function index(){
        return Inertia::render('Member/Membership',[
            'member'=>Auth()->user()->member,
        ]);
    }
}
