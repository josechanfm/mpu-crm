<?php

namespace App\Exports;

use App\Models\EnquiryQuestion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Department;
use App\Models\Config;

class EnquiryQuestionExport implements FromCollection, WithHeadings
{
    protected $enquiryQuestions;
    protected $period;

    function __construct($period)
    {
        $this->period = $period;
    }

    public function headings(): array
    {
        return ['日期','查詢編號','證件類別(持有證件)','課程類別(入讀課程)','入學途徑','姓, 名','電話','電郵','跟進人員','跟進情況','最後回應日期'];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $fields=Config::enquiryFormFields();
        $origins=array_column($fields['origin']['options'],'label','value');
        $degrees=array_column($fields['degree']['options'],'label','value');
        $admissions=array_column($fields['admission']['options'],'label','value');
        
        $department=Department::where('abbr','DAMIA')->first();
        $questions=EnquiryQuestion::with('enquiry')->with('lastResponse')
        ->whereBetween('created_at',$this->period)
            ->orderBy('created_at','desc')
            ->get();
        $data=new \Illuminate\Database\Eloquent\Collection;;
        foreach($questions as $id=>$question){
            $tmp=[
                "created_at"=>date_format($question->created_at,'Y-m-d'),
                "id"=>$question->id,
                "origin"=>explode(' ',$origins[$questions[$id]->enquiry->origin])[0],
                "degree"=>explode(' ',$degrees[$questions[$id]->enquiry->degree])[0],
                "admission"=>explode(' ',$admissions[$questions[$id]->enquiry->admission])[0],
                "fullname"=>$question->enquiry->givenname.' '.$question->enquiry->surname,
                "phone"=>$question->enquiry->phone,
                "email"=>$question->enquiry->email,
            ];
            if($question->lastResponse && $question->lastResponse->admin_user){
                $tmp['admin_user']=$question->lastResponse->admin_user->username;
                $tmp['is_closed']=$question->is_closed?'完成':'--';
                $tmp['last_response_date']=$question->lastResponse->created_at;
            }else{
                $tmp['admin_user']=null;
                $tmp['is_closed']=null;
                $tmp['last_response_date']=null;
            }
            $data->push($tmp);
        };
        return $data;
    }
}
