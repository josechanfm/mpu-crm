<?php

namespace App\Http\Controllers\Department\Dae;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Department;

class DashboardController extends Controller
{
    public function index(){
        //dd(session('department'),auth()->user());
        if(session('department')){
            return Inertia::render('Department/Dae/Dashboard',[
                'department'=>session('department')
            ]);
        }else{
            return redirect()->route('manage');
        }
    }
}
