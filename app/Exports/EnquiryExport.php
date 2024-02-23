<?php

namespace App\Exports;

use App\Models\Enquiry;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Department;
use App\Models\Config;

class EnquiryExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $enquiries;
    protected $period;

    function __construct($period)
    {
        $this->period = $period;
        // dd($this->headers);
    }

    public function headings(): array
    {
        return ['日期','查詢編號','證件類別(持有證件)','課程類別(入讀課程)','入學途徑','姓, 名','電話','電郵'];
        //return $this->headers;
    }

    public function collection()
    {
        $fields=Config::enquiryFormFields();
        $origins=array_column($fields['origin']['options'],'label','value');
        $degrees=array_column($fields['degree']['options'],'label','value');
        $admissions=array_column($fields['admission']['options'],'label','value');
        $department=Department::where('abbr','DAMIA')->first();
        $enquiries=Enquiry::
            selectRaw('DATE_FORMAT(created_at,"%Y-%m-%d"), id, origin, degree, admission, concat(givenname,", ",surname) as fullname, phone, email')
            ->whereBelongsTo($department)
            ->whereBetween('created_at',$this->period)
            ->orderBy('created_at','desc')
            ->get();
        foreach($enquiries as $id=>$enquiry){
            $enquiries[$id]->origin=explode(' ',$origins[$enquiries[$id]->origin])[0];
            $enquiries[$id]->degree=explode(' ',$degrees[$enquiries[$id]->degree])[0];
            $enquiries[$id]->admission=explode(' ',$admissions[$enquiries[$id]->admission])[0];
        }
        return collect($enquiries);
    }
}
