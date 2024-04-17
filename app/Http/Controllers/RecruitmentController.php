<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\RecVacancy;

class RecruitmentController extends Controller
{
    public function index(){
        return Inertia::render('Recruitment/Vacancies',[
            'vacancies'=>RecVacancy::with('notices')->get()
        ]);
    }
    public function apply(Request $request){
        $vacancy=RecVacancy::find($request->rec_vacancy_id);
        
        return Inertia::render('Recruitment/Apply',[
            'vacancy'=>$vacancy
        ]);
    }
}
