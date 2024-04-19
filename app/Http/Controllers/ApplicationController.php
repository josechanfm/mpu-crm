<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\RecVacancy;
use App\Models\RecApplication;
use App\Models\RecEducation;
use App\Models\RecExperience;
use App\Models\RecProfessional;

class ApplicationController extends Controller
{

    public function apply(Request $request){
        $vacancy=RecVacancy::where('code',$request->code)->first();
        $application=RecApplication::whereBelongsTo($vacancy,'vacancy')->with('user')->first();
        if(empty($application)){
            $application=RecApplication::make();
            $application->rec_vacancy_id=$vacancy->id;
            $application->user_id=auth()->user()->id;
            $application->user;
        }
        if($application->id==null){
            return Inertia::render('Recruitment/FormOne',[
                'vacancy'=>$vacancy,
                'application'=>$application
            ]);
        }

        if(empty($request->page) || $application->submitted){
            $application->educations;
            return Inertia::render('Recruitment/FormZero',[
                'vacancy'=>$vacancy,
                'application'=>$application
            ]);
        }
        if($request->page==1 && $application->id){
            $application->educations;
            return Inertia::render('Recruitment/FormOne',[
                'vacancy'=>$vacancy,
                'application'=>$application
            ]);
        }

        if($request->page && $request->page==2 && $application->id){
            $application->educations;
            return Inertia::render('Recruitment/FormTwo',[
                'vacancy'=>$vacancy,
                'application'=>$application
            ]);
        }
        if($request->page && $request->page==3 && $application->id){
            $application->experiences;
            return Inertia::render('Recruitment/FormThree',[
                'vacancy'=>$vacancy,
                'application'=>$application
            ]);
        }
        if($request->page && $request->page==4 && $application->id){
            $application->professionals;
            return Inertia::render('Recruitment/FormFour',[
                'vacancy'=>$vacancy,
                'application'=>$application
            ]); 
        }
        if($request->page && $request->page==5 && $application->id){
            $application->uploads;
            return Inertia::render('Recruitment/FormFive',[
                'vacancy'=>$vacancy,
                'application'=>$application
            ]);
        }

    }

    public function save(Request $request){
        $data=$request->all();
        $toPage=1;
        if($data['id']){
            if(isset($data['educations'])){
                foreach($data['educations'] as $edu){
                    RecEducation::find($edu['id'])->update($edu);
                }
                $toPage=3;
            }else if(isset($data['experiences'])){
                foreach($data['experiences'] as $exp){
                    RecExperience::find($exp['id'])->update($exp);
                }
                $toPage=4;
            }else if(isset($data['professionals'])){
                foreach($data['professionals'] as $pro){
                    RecProfessional::find($pro['id'])->update($pro);
                }
                $toPage=5;
            }else if(isset($data['uploads'])){
                foreach($data['uploads'] as $upload){
                    dd($upload);
                    //RecExperience::find($edu['id'])->update($exp);
                }
                $toPage=0;
            }else{
                RecApplication::find($data['id'])->update($data);
                $toPage=2;
            }
        }else{
            RecApplication::create($data);
            $toPage=2;
        }
        //dd($toPage);
                
        return redirect()->route('application.apply',[
            'code'=>RecVacancy::find($data['rec_vacancy_id'])->code,
            'page'=>$toPage
        ]);
    }

    public function submit(Request $request){
        $application=RecApplication::find($request->id);
        $application->submitted=true;
        $application->save();
        return redirect()->route('application.payment',['application_id'=>$application]);
    }

    public function payment(Request $request){
        $application=RecApplication::find($request->id);
        return Inertia::render('Recruitment/Payment',[
            'application'=>$application
        ]);

    }

}
