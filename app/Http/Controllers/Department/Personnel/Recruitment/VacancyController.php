<?php

namespace App\Http\Controllers\Department\Personnel\Recruitment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Config;
use App\Models\RecVacancy;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;

class VacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Department/Personnel/Recruitment/Vacancies',[
            'vacancies'=>RecVacancy::paginate()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Department/Personnel/Recruitment/Vacancy',[
            'workflows'=> Http::post('172.25.5.26/wms/api/recruitment/workflows')->json(),
            'vacancy'=>RecVacancy::make(),
            'educations'=>Config::item('rec_educations'),
            'vehicles'=>Config::item('rec_vehicles')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        RecVacancy::create($request->all());
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(RecVacancy $vacancy)
    {
        return Inertia::render('Department/Personnel/Recruitment/Vacancy',[
            'workflows'=> Http::post('172.25.5.26/wms/api/recruitment/workflows')->json(),
            'vacancy'=>$vacancy,
            'educations'=>Config::item('rec_educations'),
            'vehicles'=>Config::item('rec_vehicles')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RecVacancy $vacancy)
    {
        $vacancy->update($request->all());
        return redirect()->route('personnel.recruitment.vacancies.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(RecVacancy $vacancy)
    {
        $vacancy->delete();
        return redirect()->back();

    }
}
