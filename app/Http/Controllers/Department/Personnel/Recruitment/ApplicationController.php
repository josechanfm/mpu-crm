<?php

namespace App\Http\Controllers\Department\Personnel\Recruitment;

use App\Http\Controllers\Controller;
use App\Models\RecApplication;
use App\Models\RecVacancy;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Member;
use App\Models\User;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RecVacancy $vacancy, Request $request)
    {
        return Inertia::render('Department/Personnel/Recruitment/Applications',[
            'vacancy'=>$vacancy,
            'applications'=>RecApplication::whereBelongsTo($vacancy,'vacancy')->with('paid')->paginate()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(RecVacancy $vacancy)
    {
        dd('Admin Add application on behalf of candidate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RecVacancy $vacancy, Request $request)
    {
        dd($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(RecVacancy $vacancy, RecApplication $application)
    {
        $application->educations;
        $application->professionals;
        $application->experiences;
        $application->uploads;
        return Inertia::render('Department/Personnel/Recruitment/SummaryAdmin',[
            'vacancy'=>$vacancy,
            'application'=>$application
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(RecVacancy $vacancy, RecApplication $application)
    {
        session(['masquerade'=>$application->user]);
        return to_route('recruitment.admin.apply',["code"=>$vacancy->code]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(RecVacancy $vacancy, RecApplication $application)
    {
        dd('not now!');

    }

    public function quitMasquerade(){
        session()->forget('masquerade');
        return redirect()->back();
    }

    public function checkIdNum(Request $request){
        $this->validate($request,[
            'vacancy_code'=>'required',
            'id_num' => 'required',
        ]);
        $vacancy=RecVacancy::where('code',$request->vacancy_code)->first();
        return RecApplication::where('rec_vacancy_id',$vacancy->id)->where('id_num',$request->id_num)->get();
    }
    public function checkEmail(Request $request){
        $this->validate($request,[
            'vacancy_code'=>'required',
            'email' => 'required',
        ]);
        $vacancy=RecVacancy::where('code',$request->vacancy_code)->first();
        return [
            'application'=>RecApplication::where('rec_vacancy_id',$vacancy->id)->where('email',$request->email)->first(),
            'member'=>Member::where('email',$request->email)->first(),
            'user'=>User::where('email',$request->email)->first(),
        ];
    }
}
