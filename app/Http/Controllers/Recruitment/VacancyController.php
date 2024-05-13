<?php

namespace App\Http\Controllers\Recruitment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\RecVacancy;

class VacancyController extends Controller
{
    public function index(){
        dd(RecVacancy::all());
        return Inertia::render('Recruitment/Vacancies',[
            'vacancies'=>RecVacancy::with('notices')->get()
        ]);
    }
    public function form(Request $request){
        $vacancy=RecVacancy::where($request->cod);
        
        return Inertia::render('Recruitment/Apply',[
            'vacancy'=>$vacancy
        ]);
    }
}
