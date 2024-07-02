<?php

namespace App\Http\Controllers\Department\Personnel\Recruitment;

use App\Http\Controllers\Controller;
use App\Models\RecVacancy;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(){
        return Inertia::render('Department/Personnel/Recruitment/Dashboard',[
            'vacancies'=>RecVacancy::all()
        ]);
    }
}
