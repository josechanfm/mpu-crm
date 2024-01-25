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
use Maatwebsite\Excel\Facades\Excel;


class GpdpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $gpdp=Gpdp::find(1);
        // $email=[
        //     'sender'=>'personnel@mpu.edu.mo',
        //     'receiver'=>$gpdp->email,
        //     'subject'=>'abc',
        //     'content'=>view('emails.gpdpReminder',$gpdp)->render()
        // ];
        // // //return view('emails.gpdpReminder',$gpdp); 
        // // $html=view('emails.gpdpReminder',$gpdp)->render();
        // // echo $html;
        // $gpdp->emails()->create($email);
        // dd($gpdp);
        return Inertia::render('Department/Personnel/Gpdps',[
            'department'=>session('department'),
            'gpdps'=>Gpdp::paginate($request->per_page??10)
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
        if (empty($request->per_page)) {
            $per_page = 10;
        } else {
            $per_page = $request->per_page;
        }

        return Inertia::render('Department/Personnel/GpdpsEmails',[
            'emails'=>Email::where('emailable_type','App\Models\Gpdp')->paginate(2)
        ]);
    }
    public function export(){

        $columnHeaders = ["姓名(zh)","姓名(fr)","身份證明文件編號","現任職的實體/部門","現職位/職級/職務","原任職部門","原職位","任用或聘用方式","產生申報義務日期"];
        $gpdps=Gpdp::select(['name_zh','name_fr','id_num','current_department','current_position','original_department','original_position','employment_type','date_start'])->get();
        return Excel::download(new GpdpExport($gpdps,$columnHeaders), 'member.xlsx');
    }

}
