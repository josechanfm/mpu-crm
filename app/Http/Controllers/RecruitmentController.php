<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\RecVacancy;
use App\Models\RecApplication;

class RecruitmentController extends Controller
{
    public function index(){
        return Inertia::render('Recruitment/Vacancies',[
            'vacancies'=>RecVacancy::with('notices')->get()
        ]);
    }
    public function apply(Request $request){
        $vacancy=RecVacancy::where('code',$request->code)->first();
        
        return Inertia::render('Recruitment/Apply',[
            'user'=>auth()->user(),
            'vacancy'=>$vacancy,
            //'application'=>RecApplication::whereBelongsTo(auth()->user())->first()
        ]);
    }
    public function submit(Request $request){
        $data=$request->all();
        $data['user_id']=1;
        $data['rec_vacancy_id']=1;
        RecApplication::create($data);
        return redirect()->back();
    }
}
