<?php

namespace App\Http\Controllers\Department\Registry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Department;
use App\Models\EnquiryQuestion;

class DashboardController extends Controller
{
    public function index(){
        if(!session('department')){
            session(['department'=>auth()->user()->departments->first()]);
        };
        return Inertia::render('Department/Registry/Dashboard',[
            'department'=>session('department')
        ]);
    }
}
