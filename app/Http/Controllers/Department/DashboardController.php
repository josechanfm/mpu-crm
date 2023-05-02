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

    public function index(Department $department)
    {
        //$this->authorize('view',$department);
        //session(['organization' => $organization]);
        return Inertia::render('Department/Dashboard',[
            'department' => $department
        ]);

    }
    
}
