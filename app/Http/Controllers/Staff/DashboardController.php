<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Form;

class DashboardController extends Controller
{
    public function index(){
        return Inertia::render('Staff/Dashboard',[
            'departments'=>Department::where('default_route','!=','staff')->get(),
            'forms'=>Form::where('published',true)->where('for_staff',true)->get()
        ]);
    }
}
