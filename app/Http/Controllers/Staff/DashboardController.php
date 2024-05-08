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
        foreach (getallheaders() as $name => $value) {
            echo "$name: $value\n";
            echo '<br>';
        }
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        echo $ip;
        return Inertia::render('Staff/Dashboard',[
            'departments'=>Department::whereNull('default_route')->get(),
            'forms'=>Form::where('published',true)->where('for_staff',true)->get()
        ]);
    }
}
