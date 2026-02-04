<?php

namespace App\Http\Controllers\Department\Personnel\Recruitment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RecVacancy;
use App\Models\RecNotice;
use App\Models\RecApplication;

class MigrationController extends Controller
{
    public function index(Request $request){
        // dd($request->all());
        if($request->has('action')){
            switch($request->action){
                case 'vacancy':
                    $this->copyVacancies(); 
                    break;
                case 'notice':
                    $this->copyNotices(); 
                    break;
                case 'academic':
                    $this->copyAcademics(); 
                    break;
                case 'adminitratiion':
                    $this->copyAdministration(); 
                    break;
                default:
                    break;
            };
        };
        echo 'Recruitment Data Migration<br>';
        echo 'Vacancies table to rec_vacancies<br>';
        echo '\personnel\recruitment\migration\action?=vacancy<br>';
        return true;
    }

    public function copyAcademics(){
        // $model = new RecApplication();
        // $tableName = $model->getTable();
        // $columns = $model->getConnection()->getSchemaBuilder()->getColumnListing($tableName);
        // dd('vacancy data columns: ',$columns);
        $academics=DB::table('academic_applications')->get();
        foreach($academics as $academic){
            $vacancy=RecVacancy::where('code',$academic->vacancy_code)->first();
            $vacancy->applications()->create([
                "address" => $academic->address,
                "admin_id" => $academic->admin_id,
                "created_at" => $academic->created_at,
                "dob" => $academic->dob,
                "email" => $academic->email,
                "exam_lang" => $academic->exam_lang,
                "gender" => $academic->gender,
                "id" => $academic->id,
                "id_issue" => $academic->id_issue,
                "id_num" => $academic->id_num,
                "id_type" => $academic->id_type,
                "id_type_name" => $academic->id_type_name,
                "language" => $academic->language,
                "name_family_fn" => $academic->name_family_fn,
                "name_family_zh" => $academic->name_family_zh,
                "name_full_fn" => $academic->name_full_fn,
                "name_full_zh" => $academic->name_full_zh,
                "name_given_fn" => $academic->name_given_fn,
                "name_given_zh" => $academic->name_given_zh,
                "nationality" => $academic->nationality,
                "nationality_oth" => $academic->nationality_oth,
                "obtain_info" => $academic->obtain_info,
                "payment" => $academic->payment,
                "payment_id" => $academic->payment_id,
                "phone" => $academic->phone,
                "pob" => $academic->pob,
                "pob_oth" => $academic->pob_oth,
                "quick_pay" => $academic->quick_pay,
                "rec_vacancy_id" => $academic->rec_vacancy_id,
                "status" => $academic->status,
                "submitted" => $academic->submitted,
                "supplement" => $academic->supplement,
                "updated_at" => $academic->updated_at,
                "user_id" => $academic->user_id,
                "uuid" => $academic->uuid,
            ]);
        }
        

    //academic_applications
    //   +"id": 35
    //   +"applicant_id": 106
    //   +"vacancy_code": "1920-CJT-005"
    //   +"info_received": "{"info_received_pap_name":"","info_received_oth_name":""}"
    //   +"name_full_zh": "陳小文"
    //   +"name_family_zh": "陳"
    //   +"name_given_zh": "小文"
    //   +"name_family_en": "CHAN"
    //   +"name_given_en": "SIO MAN"
    //   +"gender": "M"
    //   +"pob": "CHN"
    //   +"pob_oth": ""
    //   +"dob": "1980-01-01"
    //   +"id_type": "CHN"
    //   +"id_type_name": null
    //   +"id_num": "A123456789"
    //   +"id_issue": "廣州"
    //   +"nationality": "CHN"
    //   +"nationality_oth": ""
    //   +"address": "廣州街1號廣東大廈第10座1樓A"
    //   +"tel": "(86)15348726999"
    //   +"email": "pes103@ipm.edu.mo"
    //   +"list_partB": "["{\"partB_name\":\"\u6e05\u83ef\u5927\u5b78\",\"partB_country\":\"\u4e2d\u570b\",\"partB_qualifications\":\"\u535a\u58eb\",\"partB_major\":\"\u5316\u5b78\",\"p ▶"
    //   +"list_partC": "["{\"partC_name\":\"\u570b\u5bb6\u8cea\u6aa2\u5c40\",\"partC_country\":\"\u4e2d\u570b\",\"partC_qualifications\":\"\u5316\u5b78\u6aa2\u9a57\u54e1\",\"partC_area ▶"
    //   +"list_partD": "["{\"partD_name\":\"\u570b\u5bb6\u8cea\u6aa2\u5c40\",\"partD_region\":\"\u4e2d\u570b\",\"partD_position\":\"\u5316\u5b78\u5e2b\",\"partD_salary\":\"500000\",\"p ▶"
    //   +"uploads": "{"upload_birm":[{"file_name":"9577f838fc30d480d6d8328f54b6dcd4.pdf","file_type":"application\/pdf","file_path":"\/var\/www\/html\/store\/uploads\/recruitment_ac ▶"
    //   +"supplement": null
    //   +"status": null
    //   +"submit_date": "2020-06-04 17:30:05"
    //   +"submitted": 0
    //   +"admin_id": 0
    //   +"provisional": null
    //   +"finalized": null
    //   +"created_at": "2020-06-04 17:30:05"
    //   +"updated_at": null
    }

    public function copyVacancies(){
        // $model = new RecVacancy();
        // $tableName = $model->getTable();
        // $columns = $model->getConnection()->getSchemaBuilder()->getColumnListing($tableName);
        // dd('vacancy data columns: ',$columns);

        $vacancies = DB::table('vacancies')->get();
        RecVacancy::truncate();
        foreach($vacancies as $item){
            if($item->type==null) continue;
            RecVacancy::create([
                "type" => $item->type,
                "code" => $item->code,
                "title_zh" => $item->title_zh,
                "title_pt" => $item->title_pt,
                "title_en" => $item->title_en,
                "education" => $item->education,
                "vehicle" => $item->vehicle,
                "apply_in" => $item->apply_in,
                "apply_title" => $item->apply_title,
                "description" => $item->description,
                "date_start" => $item->start,
                "date_end" => $item->end,
                "supplement_end" => $item->supplement_start,
                "supplement_start" => $item->supplement_end,
                "date_publish" => $item->public_date,
                "attach_en" => $item->attach_e,
                "attach_pt" => $item->attach_p,
                "attach_zh" => $item->attach_c,
                "process" => $item->process,
                "active" => $item->active,
                "fee" => null
            ]);
        }

        echo 'Vacancy copied complitely!<br>';
    }

    public function copyNotices(){
        // $model = new RecNotice();
        // $tableName = $model->getTable();
        // $columns = $model->getConnection()->getSchemaBuilder()->getColumnListing($tableName);
        // dd('vacancy data columns: ',$columns);

        $vacancies = DB::table('vacancies')->get();   
        //dd($vacancies);
        foreach($vacancies as $vacancy){
            if(empty($vacancy->code)) continue;
            $recVacancy=RecVacancy::where('code',$vacancy->code)->first();

            $notifications=DB::table('notifications')->where('vacancy_id',$vacancy->id)->get();
            // if($notifications->isNotEmpty()){
            //     dd($notifications[0]);
            // };
            if ($notifications->count() == 0) continue;
            
            foreach($notifications as $notification){
                $recVacancy->notices()->create([
                    "category_code"=>$notification->category_code,
                    "title_en"=>$notification->title_en,
                    "title_pt"=>$notification->title_pt,
                    "title_zh"=>$notification->title_zh,
                    "date_start"=>$notification->start_date,
                    "date_end"=>$notification->end_date,
                    "file_en"=>$notification->file_en,
                    "file_pt"=>$notification->file_pt,
                    "file_zh"=>$notification->file_zh,
                    "can_download"=>$notification->download,
                    "published"=>$notification->publish,
                    "user_id"=>$notification->user_id,
                    "apply_button"=>$notification->apply_button,
                ]);
            }
        }
    }
}
