<?php

namespace App\Http\Controllers\Recruitment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\RecVacancy;
use App\Models\RecApplication;
use App\Models\RecEducation;
use App\Models\RecExperience;
use App\Models\RecProfessional;
use App\Models\RecUpload;
use LdapRecord\Query\Events\Read;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    public function form(Request $request){
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
            return Inertia::render('Recruitment/Academic/FormOne',[
                'vacancy'=>$vacancy,
                'application'=>$application
            ]);
        }
        if($application->submitted){
            $application->educations;
            return Inertia::render('Recruitment/Academic/FormSix',[
                'vacancy'=>$vacancy,
                'application'=>$application
            ]);
        }

        if(empty($page)){
            $application->educations;
            return Inertia::render('Recruitment/Academic/FormOne',[
                'vacancy'=>$vacancy,
                'application'=>$application
            ]);
        }

        if($page==1 && $application->id){
            $application->educations;
            return Inertia::render('Recruitment/Academic/FormOne',[
                'vacancy'=>$vacancy,
                'application'=>$application
            ]);
        }

        if($page && $page==2 && $application->id){
            $application->educations;
            return Inertia::render('Recruitment/Academic/FormTwo',[
                'vacancy'=>$vacancy,
                'application'=>$application
            ]);
        }
        if($page && $page==3 && $application->id){
            $application->professionals;
            return Inertia::render('Recruitment/Academic/FormThree',[
                'vacancy'=>$vacancy,
                'application'=>$application
            ]);
        }
        if($page && $page==4 && $application->id){
            $application->experiences;
            return Inertia::render('Recruitment/Academic/FormFour',[
                'vacancy'=>$vacancy,
                'application'=>$application
            ]); 
        }
        if($page && $page==5 && $application->id){
            $application->uploads;
            return Inertia::render('Recruitment/Academic/FormFive',[
                'vacancy'=>$vacancy,
                'application'=>$application
            ]);
        }
        if($page && $page==6 && $application->id){
            $application->educations;
            $application->professionals;
            $application->experiences;
            $application->uploads;
            return Inertia::render('Recruitment/Academic/FormSix',[
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
            }else{
                echo response()->json(['message'=>'What do you wanna actually?']);
            }
        }
        //dd($toPage);
                
        return redirect()->route('recruitment.application.form',[
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

    public function fileUpload(Request $request){
        //echo 'abc';
        // echo json_encode($request->all());
        // echo json_encode($request->file('file'));
        // return true;
         
        // return response()->json(['message'=>'Upload was successfuly completed!']);
        $data=$request->all();
        if($request->hasFile('file')){
            $file=$request->file('file');
            $data['file_name']=$data['rec_application_id'].'_'.Str::uuid();
            $data['path']='/recruitment/academic/';
            $data['full_path']=$data['path'].$data['file_name'];
            $data['original_name']=$file->getClientOriginalName();
            $file->move(public_path($data['path']), $data['file_name']);
            RecUpload::create($data);
            //return response()->json(['message'=>'other Upload was successfuly completed!']);    
        }
        return redirect()->back();
    }
    public function fileDelete(RecUpload $recUpload){
        $recUpload->delete();
        return redirect()->back();
    }

}
