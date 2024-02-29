<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Form;
use App\Models\Entry;
use App\Models\EntryRecord;
use App\Models\Event;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FormController extends Controller
{
    public function __construct()
    {
        //$this->authorizeResource(Department::class);
        //$this->authorizeResource(Form::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->hasRole('admin|master')){
            $forms=Form::all();
        }else{
            if(!empty(session('department'))){
                $departments=Department::whereIn('abbr',auth()->user()->roles->pluck('name'))->get();
                session(['department'=>$departments[0]]);
                $forms=Form::whereIn('department_id',$departments->pluck('id'))->get();
            }else{
                $forms=Form::where('department_id',session('department')->id)->get();
            }
            
        }
        return Inertia::render('Department/Forms',[
            'departments'=>Department::all(),
            'forms'=>$forms
            //'forms'=>Department::find(session('department')->id)->forms
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form=Form::make([
            'department_id'=>session('department')->id??null,
            'require_login'=>false,
            'for_staff'=>false,
            'published'=>false
        ]);
        $form->media;
        return Inertia::render('Department/Form',[
            'departments'=>Department::all(),
            'form'=>$form
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
        $this->validate($request,[
            'department_id' => 'required',
            'name'=>'required',
            'title'=>'required',
        ]);
        Form::create($request->all());
        return to_route('manage.forms.index');
        return redirect()->route('manage.forms.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department, Form $form)
    {
        // $this->authorize('view',$department);
        // $this->authorize('view',$form);

        echo 'edit form';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Form $form)
    {
        $this->authorize('view',$form);
        $form->media;
        return Inertia::render('Department/Form',[
            'departments'=>Department::orderBy('abbr')->get(),
            'form'=>$form
        ]);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Form $form)
    {
        $this->validate($request,[
            'department_id' => 'required',
            'name'=>'required',
            'title'=>'required',
        ]);
        $form->update($request->all());
        if($request->file('image')){
                $form->addMedia($request->file('image')[0]['originFileObj'])->toMediaCollection('form_banner');
            
        }
        return redirect()->route('manage.forms.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department, Form $form)
    {
        if($form->hasChild()){
            return redirect()->back()->withErrors(['message'=>'No permission or restriced deletion of records with child records.']);
        }else{
            $form->delete();
            return redirect()->back();
        }
    }
    public function backup(Form $form){
        //return response()->json($form);
        $data=new \stdClass();
        $form->fields;
        //$data->form=Form::with('fields')->find($formId);
        $data->form=$form;

        if($data->form){
            $data->entries=Entry::where('form_id',3)->with('records')->get();
            $file=\Storage::put('dbbackup/'.$data->form->department_id.'/form_'.$data->form->id.'_'.time().'.txt',json_encode($data));
            if($file){
                $data->entries;
            };
            //dd($file);
            $ids=Entry::where('form_id',$data->form->id)->pluck('id')->toArray();
            EntryRecord::whereIn('entry_id',$ids)->delete();
            Entry::where('form_id',$data->form->id)->delete();
        }
        return response()->json($data);
    }

    public function deleteMedia(Media $media){
        $media->delete();
    }


}