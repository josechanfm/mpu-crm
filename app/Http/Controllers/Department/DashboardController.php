<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\AdminUser;
use App\Models\Department;

class DashboardController extends Controller
{
    public function __construct()
    {
        //$this->authorizeResource(Department::class);
    }

    public function index()
    {
        if(auth()->user()->hasRole('master')){
            $departments=Department::all();
        }else{
            $departments=auth()->user()->departments;
        }
        $count=$departments->count();

        if($count==0){
            return redirect('/');
        }else if($count==1){
            return Inertia::render('Department/Dashboard',[
                'department' => auth()->user()->departments[0],
            ]);
        }else{
            return Inertia::render('Department/Selection',[
                'departments' => $departments
            ]);
        }

    }
    
}
