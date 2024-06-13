<?php

namespace App\Http\Controllers\Recruitment;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\RecVacancy;
use App\Models\RecApplication;
use App\Models\RecEducation;
use App\Models\RecExperience;
use App\Models\RecProfessional;
use App\Models\RecUpload;
use App\Models\RecPayment;
use App\Models\RecPaymentReturn;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use LdapRecord\Query\Events\Read;
use App\Exports\RecAcademicFormExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class AcademicController extends Controller
{
    public function apply(Request $request){
        if(session('masquerade')){
            $user=session('masquerade');
        }else{
            $user=auth()->user();
        }

        if(!isset($user)){
            session(['url_intended'=>'/recruitment/academic/apply?code='.$request->code]);
            return to_route('login');
        }else{
            session()->forget('url_intended');
        }
        $vacancyCode=$request->code;
        $page=$request->page;
        $vacancy=RecVacancy::where('code',$vacancyCode)->first();
        if(empty($vacancy)){
            return to_route('recruitment');
        }
        $application=RecApplication::whereBelongsTo($user)->where('rec_vacancy_id',$vacancy->id)->first();
        if(empty($application)){
            $application=RecApplication::make();
            $application->rec_vacancy_id=$vacancy->id;
            $application->user_id=$user->id;
            //$application->user=$user;
        }
        if($application->id==null){
            return Inertia::render('Recruitment/Academic/FormOne',[
                'vacancy'=>$vacancy,
                'application'=>$application,
                'masquerade'=>session('masquerade')
            ]);
        }
        if(($page && $page==6 && $application->id ) || $application->submitted){
            $application->educations;
            $application->professionals;
            $application->experiences;
            $application->uploads;
            return Inertia::render('Recruitment/Academic/FormSix',[
                'vacancy'=>$vacancy,
                'application'=>$application,
                'masquerade'=>session('masquerade')
            ]);
        }

        if(empty($page)){
            $application->educations;
            return Inertia::render('Recruitment/Academic/FormOne',[
                'vacancy'=>$vacancy,
                'application'=>$application,
                'masquerade'=>session('masquerade')
            ]);
        }

        if($page==1 && $application->id){
            $application->educations;
            return Inertia::render('Recruitment/Academic/FormOne',[
                'vacancy'=>$vacancy,
                'application'=>$application,
                'masquerade'=>session('masquerade')
            ]);
        }

        if($page && $page==2 && $application->id){
            $application->educations;
            return Inertia::render('Recruitment/Academic/FormTwo',[
                'vacancy'=>$vacancy,
                'application'=>$application,
                'masquerade'=>session('masquerade')
            ]);
        }
        if($page && $page==3 && $application->id){
            $application->professionals;
            return Inertia::render('Recruitment/Academic/FormThree',[
                'vacancy'=>$vacancy,
                'application'=>$application,
                'masquerade'=>session('masquerade')
            ]);
        }
        if($page && $page==4 && $application->id){
            $application->experiences;
            return Inertia::render('Recruitment/Academic/FormFour',[
                'vacancy'=>$vacancy,
                'application'=>$application,
                'masquerade'=>session('masquerade')
            ]); 
        }
        if($page && $page==5 && $application->id){
            $application->uploads;
            return Inertia::render('Recruitment/Academic/FormFive',[
                'vacancy'=>$vacancy,
                'application'=>$application,
                'masquerade'=>session('masquerade')
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
                //dd($application);
                // foreach($application['uploads'] as $upload){
                //     //RecExperience::find($edu['id'])->update($exp);
                // }
            }else{
                echo response()->json(['message'=>'What do you wanna actually?']);
            }
        }
        // dd($toPage);
        return redirect()->route('recruitment.academic.apply',[
            'code'=>RecVacancy::find($application['rec_vacancy_id'])->code,
            'page'=>$toPage
        ]);
    }

    public function fileUpload(Request $request){
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

    public function submit(Request $request){
        $app=$request->application;
        $application=RecApplication::find($app['id']);
        $application->submitted=true;
        $application->save();
         return redirect()->route('recruitment.academic.success',array('application_id'=>$application->id,'uuid'=>$application->uuid));
    }

    public function success(Request $request){
        $application=RecApplication::find($request->application_id);
        if($application->user_id != auth()->user()->id || $application->uuid != $request->uuid){
            // https://epay.mpu.edu.mo/bocpaytest/api/boc/cashier
            return Inertia::render('Error',[
                'message'=>'Permission denied!'
            ]);
        }
        $vacancy=RecVacancy::find($application->rec_vacancy_id);
        return Inertia::render('Recruitment/Academic/Success',[
            'vacancy'=>$vacancy,
            'application'=>$application,
        ]);
    }
    public function receipt(Request $request){
        $application=RecApplication::find($request->application_id);
        return Excel::download(new RecAcademicFormExport($application), 'abc123.xlsx');

        $application=RecApplication::with('vacancy')->with('educations')->with('experiences')->with('professionals')->with('uploads')->find($request->application_id);
        $path=storage_path('../lang/recruitment_academic.json');
        $lang=json_decode(file_get_contents($path),true)[session('applocale')];
        $application=RecApplication::with('vacancy')->with('educations')->with('experiences')->with('professionals')->with('uploads')->find($request->application_id);
        // return view('recruitment.academicForm',[
        //     'lang'=>(Object)$lang,
        //     'application'=>$application
        // ]);
        $pdf=PDF::loadView('recruitment.academicForm',[
            'lang'=>(Object)$lang,
            'application'=>$application,
        ]);
        $pdf->render();
        return $pdf->stream('test.pdf',array('Attachment'=>false));
    }


}
