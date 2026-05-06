<?php

namespace App\Http\Controllers\Department\Saf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Department;

class DashboardController extends Controller
{
    public function index(){
        //dd(session('department'),auth()->user());
        // if(session('department')){
        session(['department'=>Department::where('abbr','SAF')->first()]);
        //dd(session('department'));
        return Inertia::render('Department/Saf/Dashboard',[
            'department'=>session('department')
        ]);
        // }else{
        //     return redirect()->route('manage');
        // }
    }
}
