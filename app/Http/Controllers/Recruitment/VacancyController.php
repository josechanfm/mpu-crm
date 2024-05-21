<?php

namespace App\Http\Controllers\Recruitment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\RecVacancy;

class VacancyController extends Controller
{
    public function index(){
        return Inertia::render('Recruitment/Vacancies',[
            'vacancies'=>RecVacancy::with('notices')->get(),
            'masquerade'=>session('masquerade')
        ]);
    }
}
