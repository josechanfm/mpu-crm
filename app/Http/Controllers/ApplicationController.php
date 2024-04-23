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
        $vacancyCode=$request->code;
        $page=$request->page;
        $vacancy=RecVacancy::where('code',$vacancyCode)->first();
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
        if($application->submitted){
            $application->educations;
            return Inertia::render('Recruitment/FormSix',[
                'vacancy'=>$vacancy,
                'application'=>$application
            ]);
        }

        if(empty($page)){
            $application->educations;
            return Inertia::render('Recruitment/FormOne',[
                'vacancy'=>$vacancy,
                'application'=>$application
            ]);
        }

        if($page==1 && $application->id){
            $application->educations;
            return Inertia::render('Recruitment/FormOne',[
                'vacancy'=>$vacancy,
                'application'=>$application
            ]);
        }

        if($page && $page==2 && $application->id){
            $application->educations;
            return Inertia::render('Recruitment/FormTwo',[
                'vacancy'=>$vacancy,
                'application'=>$application
            ]);
        }
        if($page && $page==3 && $application->id){
            $application->professionals;
            return Inertia::render('Recruitment/FormThree',[
                'vacancy'=>$vacancy,
                'application'=>$application
            ]);
        }
        if($page && $page==4 && $application->id){
            $application->experiences;
            return Inertia::render('Recruitment/FormFour',[
                'vacancy'=>$vacancy,
                'application'=>$application
            ]); 
        }
        if($page && $page==5 && $application->id){
            $application->uploads;
            return Inertia::render('Recruitment/FormFive',[
                'vacancy'=>$vacancy,
                'application'=>$application
            ]);
        }
        if($page && $page==6 && $application->id){
            $application->uploads;
            return Inertia::render('Recruitment/FormSix',[
                'vacancy'=>$vacancy,
                'application'=>$application
            ]);
        }

    }

    public function save(Request $request){
        $application=$request->application;
        $toPage=$request->to_page;
        if(empty($application)){
            return redirect()->route('recruitment');
        }
        if(empty($application['id'])){
            RecApplication::create($application);
            $toPage=2;
        }else{
            $recApp=RecApplication::find($application['id']);
            if($toPage==2 || empty($toPage)){
                //dd($application);
                RecApplication::find($application['id'])->update($application);
            }elseif($toPage==3 && isset($application['educations'])){
                foreach($application['educations'] as $edu){
                    if(isset($edu['id'])){
                        RecEducation::find($edu['id'])->update($edu);
                    }else{
                        $recApp->educations()->create($edu);
                    }
                }
            }elseif($toPage==4 && isset($application['professionals'])){
                foreach($application['professionals'] as $pro){
                    if(isset($pro['id'])){
                        RecProfessional::find($pro['id'])->update($pro);
                    }else{
                        $recApp->professionals()->create($pro);
                    }
                }
            }elseif($toPage==5 && isset($application['experiences'])){
                //dd($application);
                foreach($application['experiences'] as $exp){
                    if(isset($exp['id'])){
                        RecExperience::find($exp['id'])->update($exp);
                    }else{
                        $recApp->experiences()->create($exp);
                    }
                }
            }elseif($toPage==6){
                foreach($application['uploads'] as $upload){
                    //RecExperience::find($edu['id'])->update($exp);
                }
                $toPage=7;
            }else{
                echo response()->json(['message'=>'What do you wanna actually?']);
            }
        }
        //dd($toPage);
                
        return redirect()->route('application.apply',[
            'code'=>RecVacancy::find($application['rec_vacancy_id'])->code,
            'page'=>$toPage
        ]);
    }

    public function submit(Request $request){
        $app=$request->application;
        $application=RecApplication::find($app['id']);
        
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
