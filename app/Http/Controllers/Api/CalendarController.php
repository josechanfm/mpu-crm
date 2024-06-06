<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use IcalParser\Parser;
use ICal\ICal;

class CalendarController extends Controller
{
    public function sync(Request $request){
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
            $data['title_zh']=$event->summary;
            $data['date']=date('Y-m-d',$dtstart);
            $data['time_start']=date('H:i:s',$dtstart);
            $data['time_end']=date('H:i:s',$dtend);
            echo json_encode($data);
            echo '<br>';
        }
    }
}
