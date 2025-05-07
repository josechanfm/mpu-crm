<?php

namespace App\Http\Controllers\Department\Personnel\Recruitment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\RecVacancy;
use App\Models\RecNotice;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class NoticeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RecVacancy $vacancy)
    {
        return Inertia::render('Department/Personnel/Recruitment/Notices',[
            'vacancy'=>$vacancy,
            'notices'=>RecNotice::whereBelongsTo($vacancy,'vacancy')->orderBy('date_start')->paginate()
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
            $notice=RecNotice::make([
                'rec_vacancy_id'=>$vacancy->id,
            ]);
            $notice->media=[];
            return Inertia::render('Department/Personnel/Recruitment/Notice',[
                'vacancy'=>$vacancy,
                'notice'=>$notice
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
        RecNotice::create($request->all());
        return redirect()->route('personnel.recruitment.vacancy.notices.index',$vacancy->id);
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
    public function edit(RecVacancy $vacancy, RecNotice $notice)
    {
        $notice->media;
        //dd($notice);
        if($vacancy){
            return Inertia::render('Department/Personnel/Recruitment/Notice',[
                'vacancy'=>$vacancy,
                'notice'=>$notice
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
    public function update(RecVacancy $vacancy, RecNotice $notice, Request $request)
    {
        // dd($vacancy);
        // dd($notice);

        $notice->update($request->all());
        $notice->save();

        //dd($request->all());
        $notice=RecNotice::find($request->id);
        if($request->file('upload_file_zh')){
            foreach($request->file('upload_file_zh') as $file){
                $notice->addMedia($file['originFileObj'])->toMediaCollection('noticeFileZh');
                //dd($file['originFileObj'],$notice);
            }
        };
        if($request->file('upload_file_en')){
            foreach($request->file('upload_file_en') as $file){
                $notice->addMedia($file['originFileObj'])->toMediaCollection('noticeFileEn');
            }
        };
        if($request->file('upload_file_pt')){
            foreach($request->file('upload_file_pt') as $file){
                $notice->addMedia($file['originFileObj'])->toMediaCollection('noticeFilePt');
            }
        };

        return redirect()->back();
        dd($request->file());

        if($request->file('file_en2')){
            $file=$request->file('file_en2')[0];
            $notice->file_en=Storage::disk('recruitment')->put('notices',$file['originFileObj']);
        };

        if($request->file('file_pt2')){
            $file=$request->file('file_pt2')[0];
            $notice->file_pt
            =Storage::disk('recruitment')->put('notices',$file['originFileObj']);
        };
        $notice->save();

        return redirect()->back();
        //return redirect()->route('personnel.recruitment.vacancy.notices.index',$vacancy->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(RecVacancy $vacancy, RecNotice $notice)
    {
        return redirect()->back();
    }
    public function deleteMedia(Media $media){
        $media->delete();
        return redirect()->back();
    }
}
