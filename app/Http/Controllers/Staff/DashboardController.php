<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Form;
use App\Models\AdminUser;

class DashboardController extends Controller
{
    public function index(){
        // if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        //     $remoteAddress = $_SERVER['HTTP_CLIENT_IP'];
        // } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //     $remoteAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        // } else {
        //     $remoteAddress = $_SERVER['REMOTE_ADDR'];
        // }
        return Inertia::render('Staff/Dashboard',[
            'departments'=>Department::whereNotNull('default_route')->get(),
            'forms'=>Form::where('published',true)->where('for_staff',true)->get()
        ]);
    }
}
