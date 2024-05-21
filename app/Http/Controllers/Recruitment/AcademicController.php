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
            return to_route('recruitment.vacancies');
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
         return redirect()->route('recruitment.academic.payment',array('application_id'=>$application->id,'uuid'=>$application->uuid));
    }

    public function payment(Request $request){
        $application=RecApplication::find($request->application_id);
        if($application->user_id != auth()->user()->id || $application->uuid != $request->uuid){
            // https://epay.mpu.edu.mo/bocpaytest/api/boc/cashier
            return Inertia::render('Error',[
                'message'=>'Permission denied!'
            ]);
        }
        $vacancy=RecVacancy::find($application->rec_vacancy_id);
        if(empty($vacancy) && $vacancy->fee==0){
            return Inertia::render('Error',[
                'message'=>'Permission denied!'
            ]);
        }

        $systemCode=env('BOC_SYSTEM_CODE','MPUCRM');
        $mercOrderNo=$application->id.'-'.time().'-'.rand(1000,9999);
        $amount=$vacancy->fee;
        $salt=env('BOC_SALT','8Ier5T)1up]_S7)XHd(KcHwtM><cuF415P$=Dqb6}OtN_[bd');

        $payment=[
            'systemCode'=>$systemCode, //Required 授權後獲得
            'ipAddress'=>$request->getClientIp(), 
            'cashierLanguage'=>'zh_TW', //Required zh_TW或en_US
            'amount'=>$amount, //Required 交易金額(折後，如無折扣，則和originalAmount一樣即可)
            'originalAmount'=>$amount, //Required 交易原金額
            'subject'=>'Admin', //Required 交易標題
            'productDesc'=>'', //Optional 交易描述
            'mercOrderNo'=>$mercOrderNo, //Required 訂單唯一編號
            'requester'=>'Test User', //Optional 支付者名稱
            'orderDate'=>'', //Optional 請求日期。如不填，則自動填寫系統即時日期
            'orderTime'=>'', //Optional 請求時間。如不填，則自動填寫系統即時時間
            'validNumber'=>'', //Optional 交易有效時間(單位:秒)，預設為300秒
            'otherBusinessType'=>'Recruitment', //Required 交易類型
            'paymentChannel'=>'boc', //boc, cep 當有多於一個交易渠道，使用|間隔，例如boc|cep，如不填或格式錯誤，則顯示所有可用交易渠道
            'mcsSyncOrderNo'=>'', //
            'email'=>'tester@mpu.edu.mo', //交易成功後同步MCS(如有)，搜尋並更新相同orderNo的MCS記錄。
            //注1: 當你填寫了mcsSyncOrderNo，則不需要另外通過MCS paybill API做交易動作。
            //注2: MCS的交易金額不會在此步驟中發生變化，若交易時與MCS記錄創建時的金額不相同，請及時通過MCS API更新記錄。
            'signText'=>hash('sha256',$systemCode.$mercOrderNo.$amount.$salt)
            //System Code + mercOrderNo + amount + Salt使用SHA256生成的不可逆的字串。用於識別是否為經授權的系統發出的交易。
            //(2023-08-31 更新singText中加入amount以作檢查金額沒有被惡意修改)
        ];
        $data=[];
        foreach($payment as $key=>$value){
            $data[Str::snake($key)]=$value;
        };
        $data['rec_application_id']=$application->id;
        RecPayment::create($data);

        $vacancy=RecVacancy::find($application->rec_vacancy_id);
        return Inertia::render('Recruitment/Academic/Payment',[
            'vacancy'=>$vacancy,
            'application'=>$application,
            'payment'=>$payment
        ]);
    }


    public function bocOrderQuery(){
        //https://epay.mpu.edu.mo/bocpaytest/api/boc/orderquery
        //systemCode	必填	授權後獲得
        //queryNo	可選*	交易編號(mercOrderNo)
        //queryLogNo	可選*	交易查詢號碼(logNo)，與queryNo必須填一個，若兩個都不為空則只查詢queryNo
    }

    public function testBocPayment(Request $request){
        return Inertia::render('Recruitment/Academic/TestBocPayment',[
            'applications'=>RecApplication::all(),
            'payments'=>RecPayment::all(),
        ]);
    }


}
