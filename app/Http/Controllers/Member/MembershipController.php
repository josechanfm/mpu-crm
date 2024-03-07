<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MembershipController extends Controller
{
    public function index(){
        $member=Auth()->user()->member;
        //$digitLength=strlen((string)$cert->pivot->number)*-1;
        
        return Inertia::render('Member/Membership',[
            'member'=>$member,
        ]);
    }
}