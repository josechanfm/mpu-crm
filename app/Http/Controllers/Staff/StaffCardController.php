<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;

class StaffCardController extends Controller
{
    public function index(){
        $staff=Staff::where('username',auth()->user()->username)->first();
        return inertia('Staff/StaffCard',[
            'staff'=>$staff->load('relatives')
        ]);
    }
}
