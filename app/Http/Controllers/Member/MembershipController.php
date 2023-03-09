<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MembershipController extends Controller
{
    public function index(){
        $member=Auth()->user()->member;
        $certificates=Auth()->user()->member->certificates;
        return Inertia::render('Member/Membership',[
            'member'=>$member,
            'certificates'=>$certificates
        ]);
    }
}
