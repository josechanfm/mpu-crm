<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Form;
use App\Models\Department;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FormController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(Organization::class);
        // $this->authorizeResource(Form::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $department=Department::find(session('currentDepartmentId'));
        return Inertia::render('Department/Forms',[
            'department'=>$department,
            'forms'=>$department->forms
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
        $this->validate($request,[
            'name'=>'required',
            'title'=>'required',
        ]);
            $form=new Form();
            $form->department_id=session('currentDepartmentId');
            $form->name=$request->name;
            $form->title=$request->title;
            $form->description=$request->description;
            $form->require_login=$request->require_login;
            $form->for_member=$request->for_member;
            $form->published=$request->published;
            $form->save();
            return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Form $form)
    {
        $department=Department::find(session('currentDepartmentId'));
        $form=Form::with('fields')->find($form->id);
        $filleds=$form->filleds;
        $columns=[(object)['title'=>'Nubmer','dataIndex'=>'uid']];
        foreach($form->fields as $field){
            if($field->in_column){
                $columns[]=(object)['title'=>$field->field_label,'dataIndex'=>$field->field_name];
                foreach($filleds as $k=>$filled){
                    foreach($filled->fields as $ff){
                        if($ff->form_field_id==$field->id){
                            $filleds[$k][$field->field_name]=$ff->field_value;
                        }
                    }
                    
                }
            }
        }
        $columns[]=(object)['title'=>'Submit at','dataIndex'=>'created_at'];
        $columns[]=(object)['title'=>'Action','dataIndex'=>'operation'];

        return Inertia::render('Department/Filleds',[
            'department'=>$department,
            'form'=>$form,
            'filleds'=>$filleds,
            'columns'=>$columns
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Form $form)
    {
        //$this->authorize('update',$organization);
        //$this->authorize('update',$form);
        // return Inertia::render('Admin/FormEdit',[
        //     'fields'=>$form->fields
        // ]);
        
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
        $this->validate($request,[
            'id' => 'required',
            'name'=>'required',
            'title'=>'required',
            // 'image'=>'array',
            // 'image.*.originFileObj' => 'image|mimes:jpeg,jpg,gif,png|max:1024'
        ]);
        $form=Form::find($request->id);
        $form->name=$request->name;
        $form->title=$request->title;
        $form->description=$request->description;
        $form->require_login=$request->require_login;
        $form->for_member=$request->for_member;
        $form->published=$request->published;
        if($request->file('image')){
            $form->addMedia($request->file('image')[0]['originFileObj'])->toMediaCollection('image');
        }
        $form->save();
        return redirect()->back();
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Form $form)
    {
        if($form->hasChild()){
            return redirect()->back()->withErrors(['message'=>'No permission or restriced deletion of records with child records.']);
        }else{
            $form->delete();
            return redirect()->back();
        }
    }

    public function deleteMedia(Media $media){
        $media->delete();
    }    
}
