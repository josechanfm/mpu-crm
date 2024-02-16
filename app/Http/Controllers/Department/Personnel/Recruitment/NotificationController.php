<?php

namespace App\Http\Controllers\Department\Personnel\Recruitment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\RecVacancy;
use App\Models\RecNotification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RecVacancy $vacancy)
    {
        return Inertia::render('Department/Personnel/Recruitment/Notifications',[
            'vacancy'=>$vacancy,
            'notifications'=>RecNotification::whereBelongsTo($vacancy,'vacancy')->orderBy('date_start')->paginate()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(RecVacancy $vacancy)
    {
        if($vacancy){
            return Inertia::render('Department/Personnel/Recruitment/Notification',[
                'vacancy'=>$vacancy,
                'notification'=>RecNotification::make([
                    'rec_vacancy_id'=>$vacancy->id,
                ])
            ]);
        };
        return redirect()->route('personnel.recruitment.vacancies.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RecVacancy $vacancy, Request $request)
    {
        RecNotification::create($request->all());
        return redirect()->route('personnel.recruitment.vacancy.notifications.index',$vacancy->id);
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
    public function edit(RecVacancy $vacancy, RecNotification $notification)
    {
        if($vacancy){
            return Inertia::render('Department/Personnel/Recruitment/Notification',[
                'vacancy'=>$vacancy,
                'notification'=>$notification
            ]);
        };
        return redirect()->route('personnel.recruitment.vacancies.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RecVacancy $vacancy, RecNotification $notification, Request $request)
    {
        $notification->update($request->all());
        return redirect()->route('personnel.recruitment.vacancy.notifications.index',$vacancy->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
