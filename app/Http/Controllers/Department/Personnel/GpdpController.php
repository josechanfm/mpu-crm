<?php

namespace App\Http\Controllers\Department\Personnel;

use App\Http\Controllers\Controller;
use App\Mail\GpdpReminderEmail;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Gpdp;
use App\Models\Department;
use Illuminate\Support\Facades\Mail;
use App\Models\Email;
use App\Exports\GpdpExport;
use App\Imports\GpdpImport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xls\RC4;

class GpdpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $yesterday=date('Y-m-d',strtotime('-1 day'));
        $g=Gpdp::where('date_remind',$yesterday)->count();
        $m=Email::whereRaw('left(created_at,10) = "'.$yesterday.'"')->count();
        return Inertia::render('Department/Personnel/Gpdps',[
            'gpdps'=>Gpdp::orderBy('date_start','desc')->paginate($request->per_page??50),
            'yesterdaySent'=>'Sent '.$m.' of '.$g.' message(s)'

            //'gpdps'=>Gpdp::orderBy('date_remind','desc')->paginate($request->per_page??10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        Gpdp::create($request->all());
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Gpdp $gpdp, Request $request)
    {
        $gpdp->update($request->all());
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gpdp $gpdp)
    {
        $gpdp->delete();
        return redirect()->back();
    }

    public function sendEmailReminder(Gpdp $gpdp){
        Mail::to($gpdp->email)->send(new GpdpReminderEmail($gpdp));
        return redirect()->back();
    }

    public function listEmails(Request $request){
        if($request->date_range){
            $emails=Email::where('emailable_type','App\Models\Gpdp')->whereBetween('created_at',$request->date_range)->orderBy('created_at','desc')->paginate(50);
        }else{
            if($request->gpdp_id){
                $emails=Email::where('emailable_type','App\Models\Gpdp')->where('emailable_id',$request->gpdp_id)->orderBy('created_at','desc')->paginate(50);
            }else{
                $emails=Email::where('emailable_type','App\Models\Gpdp')->orderBy('created_at','desc')->paginate(50);
            }
            
        };
        
        return Inertia::render('Department/Personnel/GpdpsEmails',[
            'emails'=>$emails
        ]);
    }
    public function export(Request $request){
        $columnHeaders = ["姓名(zh)","姓名(fr)","身份證明文件編號","現職位/職級/職務","現任職的實體/部門","原職位","原任職部門","任用或聘用方式","產生申報義務日期"];
        $gpdps=Gpdp::select(['name_zh','name_fr','id_num','current_position','current_department','original_position','original_department','employment_type','date_start'])
            ->whereBetween('date_start',$request->period)
            ->get();
        foreach($gpdps as $i=>$gpdp){
            $gpdps[$i]->date_start=date_format(date_create($gpdps[$i]->date_start),'d/m/Y');
        }
        return Excel::download(new GpdpExport($gpdps,$columnHeaders), date('Y-m-d').'_gpdps.xlsx');
    }
    public function import(Request $request){
        $gpdps=Excel::import(new GpdpImport, $request->file()[0]);
        // $gpdps=Excel::toArray(new GpdpImport, $request->file()[0]);
        // dd($gpdps[0]);
        return redirect()->back();
    }

}
