<?php

namespace App\Exports;

use App\Models\RecApplication;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Files\LocalTemporaryFile;


class RecAcademicFormExport implements FromCollection, WithHeadings, WithEvents 
{
    protected $application;
    public function __construct($application)
    {
        $this->application=$application;
        $this->calledByEvent = false;
    }

    public function headings(): array
    {
        $columnHeaders = ['key','value'];
        return $columnHeaders;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $application=$this->application->toArray();
        //$application['obtain_info']=json_encode($application['obtain_info']);
        //dd($application);
        //return $application;
        $data=[];
        foreach($application as $key=>$value){
            $data[]=['key'=>$key,'value'=>$value];
        }
        return collect($data);


    }

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function(BeforeWriting $event) {
                $templateFile = new LocalTemporaryFile(storage_path('template/recruitment/academic_form.xlsx'));
                $event->writer->reopen($templateFile, Excel::XLSX);
                $event->writer->getSheetByIndex(1);
                $this->calledByEvent = true; // set the flag
                $event->writer->getSheetByIndex(1)->export($event->getConcernable()); // call the export on the first sheet

                return $event->getWriter()->getSheetByIndex(1);

            },
        ];
    }
    
}
