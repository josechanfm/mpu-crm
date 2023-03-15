<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\AdminUser;
use App\Models\Organization;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Organization::class);
    }

    public function index(Organization $organization)
    {
        $this->authorize('view',$organization);
        session(['organization' => $organization]);
        return Inertia::render('Organization/Dashboard',[
            'organization' => $organization
        ]);

    }
    
}
