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
        $cert=$certificates[0];
        echo $cert->number_format;
        //$digitLength=strlen((string)$cert->pivot->number)*-1;
        $digitLength=preg_match('/0+/',$cert->number_format);
        echo $digitLength;
        echo '<br>';
        echo preg_replace('/0+/', substr('0000000'.$cert->pivot->number, -5), $cert->number_format);
        return;
        return Inertia::render('Member/Membership',[
            'member'=>$member,
            'certificates'=>$certificates
        ]);
    }
}
