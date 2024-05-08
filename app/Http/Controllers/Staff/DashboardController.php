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
        dd($_SERVER['HTTP_CLIENT_IP']);
        dd($_SERVER['HTTP_X_FORWARDED_FOR']);
        dd($_SERVER['REMOTE_ADDR']);
        return Inertia::render('Staff/Dashboard',[
            'departments'=>Department::whereNull('default_route')->get(),
            'forms'=>Form::where('published',true)->where('for_staff',true)->get()
        ]);
    }
}
