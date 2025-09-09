<?php

namespace App\Http\Controllers\Department\Flt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Config;
use App\Models\Publication;
use Illuminate\Support\Facades\Storage;

class PublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Department/Flt/Publications',[
            'categories'=>Config::item('publication_categories')->value,
            'publications'=>Publication::all(),
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
        $data=$request->all();
        $data['department_id']=session('department')->id;
        Publication::create($data);
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
    public function update(Publication $publication, Request $request)
    {
        $data=$request->all();
        if($request->upload){
            if($publication->cover && Storage::disk('media')->exists($publication->cover)){
                Storage::disk('media')->delete($publication->cover);
            }
            $data['cover']=Storage::disk('media')->put('publications',$data['upload'][0]['originFileObj']);
        }
        //$data['cover'] = Storage::putFile('public/images/forms/photos', $data['upload'][0]['originFileObj']);
        $publication->update($data);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Publication $publication, Request $request)
    {
        if($request->type=='cover' && $publication->cover && Storage::disk('media')->exists($publication->cover)){
            Storage::disk('media')->delete($publication->cover);
            $publication->cover=null;
            $publication->save();
        }else{

        }
        return redirect()->back();
    }
}
