<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use IcalParser\Parser;
use ICal\ICal;
use App\Models\Calendar;
use Validator;
use Inertia\Inertia;

class CalendarController extends Controller
{
    public function sync(Request $request){
        $firstDayOfYear=date('Y-m-d', strtotime('first day of january this year'));
        $lastDayOfYear=date('Y-m-d', strtotime('last day of december this year'));
        $numberOfDaysOfYear=date('z', strtotime('last day of december this year'));
        
        for($i=0; $i<=$numberOfDaysOfYear; $i++){
            $data['category']='DAY';
            $data['title_zh']='Day';
            $data['date']=date(date('Y-m-d',strtotime($firstDayOfYear.' +'.$i.' day')));
            //$data['start']=date('H:i:s',$dtstart);
            // $data['end']=date('H:i:s',$dtend);s
            $data['dow']=date('N',strtotime($data['date']));
            Calendar::updateOrCreate(['date'=>$data['date']],$data);
        }

        $firstDayOfYear=date('Y-m-d', strtotime('first day of january next year'));
        $lastDayOfYear=date('Y-m-d', strtotime('last day of december next year'));
        $numberOfDaysOfYear=date('z', strtotime('last day of december next year'));
        
        for($i=0; $i<=$numberOfDaysOfYear; $i++){
            $data['category']='DAY';
            $data['title_zh']='Day';
            $data['date']=date(date('Y-m-d',strtotime($firstDayOfYear.' +'.$i.' day')));
            //$data['start']=date('H:i:s',$dtstart);
            // $data['end']=date('H:i:s',$dtend);s
            $data['dow']=date('N',strtotime($data['date']));
            Calendar::updateOrCreate(['date'=>$data['date']],$data);
        }

        try {
            $ical = new ICal('ICal.ics', array(
                'defaultSpan'                 => 2,     // Default value
                'defaultTimeZone'             => 'Asia/Macau',
                'defaultWeekStart'            => 'MO',  // Default value
                'disableCharacterReplacement' => false, // Default value
                'filterDaysAfter'             => null,  // Default value
                'filterDaysBefore'            => null,  // Default value
                'httpUserAgent'               => null,  // Default value
                'skipRecurrence'              => false, // Default value
            ));
            // $ical->initFile('ICal.ics');
            $ical->initUrl('https://www.gov.mo/zh-hant/public-holidays/ical-timestamp/', $username = null, $password = null, $userAgent = null);
        } catch (\Exception $e) {
            die($e);
        }
        $events=$ical->sortEventsWithOrder($ical->events());

        foreach($events as $event){
            $dtstart = $event->dtstart_array[2];
            $dtend = $event->dtend_array[2];
            $data['category']='PUB';
            $data['title_zh']=$event->summary;
            $data['date']=date('Y-m-d',$dtstart);
            $data['start']=date('H:i:s',$dtstart);
            $data['end']=date('H:i:s',$dtend);
            $data['dow']=date('N',$dtstart);
            Calendar::updateOrCreate(['date'=>$data['date']],$data);
        }
    }

    public function workingDays(Request $request){
        $validation = Validator::make($request->all(),[ 
            'start' => 'required',
            'end' => 'required',
        ]);
        if ($validation->fails()) {
            return 'Request parameter not correct!';
        }else{
            return Calendar::whereBetween('date',[$request->start,$request->end])->where('category','DAY')->get()->count();
        }
        
    }
    public function isHoliday(Request $request){
        $validation = Validator::make($request->all(),[ 
            'date' => 'required',
        ]);
        if ($validation->fails()) {
            return 'Request parameter not correct!';
        }else{
            return Calendar::where('date',$request->date)->where('category','<>','DAY')->first()?1:0;
        }

    }
    public function isWorkingDay(Request $request){
        $validation = Validator::make($request->all(),[ 
            'date' => 'required',
        ]);
        if ($validation->fails()) {
            return 'Request parameter not correct!';
        }else{
            return Calendar::where('date',$request->date)->where('category','DAY')->first()?1:0;
        }
    }
}
