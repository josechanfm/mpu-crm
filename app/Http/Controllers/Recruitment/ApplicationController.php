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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
                //dd($application);
                // foreach($application['uploads'] as $upload){
                //     //RecExperience::find($edu['id'])->update($exp);
                // }
            }else{
                echo response()->json(['message'=>'What do you wanna actually?']);
            }
        }
        // dd($toPage);
        return redirect()->route('recruitment.application.form',[
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
         return redirect()->route('recruitment.application.payment',array('application_id'=>$application->id,'uuid'=>$application->uuid));
    }

    public function bocPayment(Request $request){
        $application=RecApplication::find($request->application_id);
        if($application->user_id != auth()->user()->id || $application->uuid != $request->uuid){
            // https://epay.mpu.edu.mo/bocpaytest/api/boc/cashier
            $data=[
                'systemCode'=>'', //Required 授權後獲得
                'ipAddress'=>'', 
                'requester'=>'', //Optional 支付者名稱
                'cashierLanguage'=>'', //Required zh_TW或en_US
                'amount'=>'', //Required 交易金額(折後，如無折扣，則和originalAmount一樣即可)
                'originalAmount'=>'', //Required 交易原金額
                'subject'=>'', //Required 交易標題
                'productDesc'=>'', //Optional 交易描述
                'mercOrderNo'=>'', //Required 訂單唯一編號
                'orderDate'=>'', //Optional 請求日期。如不填，則自動填寫系統即時日期
                'orderTime'=>'', //Optional 請求時間。如不填，則自動填寫系統即時時間
                'validNumber'=>'', //Optional 交易有效時間(單位:秒)，預設為300秒
                'otherBusinessType'=>'', //Required 交易類型
                'merchantPreferentialCnName'=>'', //Optional 優惠中文名稱
                'merchantPreferencitalEnName'=>'', //Optional 優惠英文名稱
                'supplier'=>'', //
            ];
            return Inertia::render('Error',[
                'message'=>'Permission denied!'
            ]);
        }
        $vacancy=RecVacancy::find($application->rec_vacancy_id);
        return Inertia::render('Recruitment/Academic/Payment',[
            'vacancy'=>$vacancy,
            'application'=>$application
        ]);
    }

    public function bocResult(Request $request){
        // $validator = Validator::make($request->all(), [
        //     'iopName'=>'', //系統請求結果
        //     'iopCode'=>'', //系統請求結果代碼
        //     'iopMessage'=>'', //系統請求結果信息
        //     'returnCode'=>'', //中銀返回的代碼
        //     'returnMessage'=>'', //中銀返回交易信息
        //     'requestId'=>'', //系統流水號
        //     'logNo'=>'', //交易查詢號碼
        //     'result'=>'', //中銀請求結果(S或F)
        //     'payUrl'=>'', //若請求成功，則會返回交易鏈接
        // ]);
        echo 'abc123';
        //return response()->json($request->all());
        //'merchantOrderNo'
        // if cancel 訂單號碼(不含systemCode)
        // if fail and return , //訂單號碼(systemCode + mercOrderNo + XX) XX為兩位數字，由01 – 99，代表該訂單編號提交的次數。

    }

    public function testBocPayment(Request $request){
        $data=[
            'systemCode'=>'MPUCRM', //Required 授權後獲得
            'ipAddress'=>$request->getClientIp(), 
            'requester'=>'Test User', //Optional 支付者名稱
            'cashierLanguage'=>'zh_Tw', //Required zh_TW或en_US
            'amount'=>'300', //Required 交易金額(折後，如無折扣，則和originalAmount一樣即可)
            'originalAmount'=>'300', //Required 交易原金額
            'subject'=>'Academic', //Required 交易標題
            'productDesc'=>'', //Optional 交易描述
            'mercOrderNo'=>'test_order_num'.rand(1000,9999), //Required 訂單唯一編號
            'orderDate'=>'', //Optional 請求日期。如不填，則自動填寫系統即時日期
            'orderTime'=>'', //Optional 請求時間。如不填，則自動填寫系統即時時間
            'validNumber'=>'', //Optional 交易有效時間(單位:秒)，預設為300秒
            'otherBusinessType'=>'Recruitment', //Required 交易類型
            'merchantPreferentialCnName'=>'', //Optional 優惠中文名稱
            'merchantPreferencitalEnName'=>'', //Optional 優惠英文名稱
            'supplier'=>'', //
        ];
        //dd($data);
        $url='https://epay.mpu.edu.mo/bocpaytest/api/boc/cashier';
        $response=Http::asForm()->post($url,$data);
        echo $response;
    }

    public function notify(){

    }

    public function testBocResult(){
        $data=[
            'iopName'=>'iop_name', //系統請求結果
            'iopCode'=>'iop_code', //系統請求結果代碼
            'iopMessage'=>'', //系統請求結果信息
            'returnCode'=>'', //中銀返回的代碼
            'returnMessage'=>'', //中銀返回交易信息
            'requestId'=>'', //系統流水號
            'logNo'=>'', //交易查詢號碼
            'result'=>'', //中銀請求結果(S或F)
            'payUrl'=>'', //若請求成功，則會返回交易鏈接
        ];
        // $url=url(route('recruitment.application.bocResult'));
        // $response=Http::post($url,$data);
        dd($response);
    }

    public function bocOrderQuery(){
        //https://epay.mpu.edu.mo/bocpaytest/api/boc/orderquery
        //systemCode	必填	授權後獲得
        //queryNo	可選*	交易編號(mercOrderNo)
        //queryLogNo	可選*	交易查詢號碼(logNo)，與queryNo必須填一個，若兩個都不為空則只查詢queryNo
    }

}
