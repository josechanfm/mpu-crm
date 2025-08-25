<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\AdminUser;
use App\Models\Department;

class DashboardController extends Controller
{
    public function index(){
        // dd(Department::all());
        // dd(AdminUser::with('departments')->get());
         //dd('master', auth(), auth()->user());
        return Inertia::render('Master/Dashboard',[
            'adminUsers'=>AdminUser::with('departments')->get(),
            'departments'=>Department::all()
        ]);
    }

}
