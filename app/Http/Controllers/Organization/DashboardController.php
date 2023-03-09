<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\AdminUser;

class DashboardController extends Controller
{
    public function index()
    {
        $organizations=AdminUser::find(auth()->user()->id)->organizations;
        return Inertia::render('Organization/Dashboard',[
            'organizations' => $organizations
        ]);
    }
    
}
