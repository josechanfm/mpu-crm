<?php

namespace App\Http\Controllers\Department\Personnel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Department;

class DashboardController extends Controller
{
    public function index(){
        if(session('department')){
            return Inertia::render('Department/Personnel/Dashboard',[
                'department'=>session('department')
            ]);
        }else{
            return redirect()->route('manage');            
        }
    }
}
