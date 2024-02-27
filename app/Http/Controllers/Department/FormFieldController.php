<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Form;
use App\Models\FormField;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FormFieldController extends Controller
{
    public function __construct()
    {
        //$this->authorizeResource(Department::class);
        $this->authorizeResource(Form::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Form $form)
    {
        $this->authorize('view',$form);
        return Inertia::render('Department/FormFields',[
            'departments'=>Department::all(),
            'department'=>session('department'),
            'form'=>$form,
            'fields'=>$form->fields,
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
    public function store(Form $form, Request $request)
    {
        $this->validate($request,[
            'form_id' => 'required',
            'field_label'=>'required',
            'type'=>'required',
        ]);

        $field=new FormField();
        $field->form_id=$request->form_id;
        $field->field_name=$request->field_name;
        $field->field_label=$request->field_label;
        $field->type=$request->type;
        $field->options=json_encode($request->options);
        $field->required=isset($request->required)?$request->required:false;
        $field->in_column=isset($request->in_column)?$request->in_column:false;
        $field->direction=isset($request->direction)?$request->direction:'H';
        $field->rule=$request->rule;
        $field->validate=$request->validate;
        $field->remark=$request->remark;
        $field->save();
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
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Form $form, Request $request )
    {
        $this->validate($request,[
            'form_id' => 'required',
            'field_label'=>'required',
            'type'=>'required',
        ]);
        $field=FormField::find($request->id);
        $field->form_id=$request->form_id;
        $field->field_name=$request->field_name;
        $field->field_label=$request->field_label;
        $field->type=$request->type;
        $field->options=json_encode($request->options);
        $field->direction=isset($request->direction)?$request->direction:'H';
        $field->required=isset($request->required)?$request->required:false;
        $field->in_column=isset($request->in_column)?$request->in_column:false;
        $field->remark=$request->remark;
        $field->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department, Form $form, FormField $field)
    {
        $field->delete();        
    }

    public function fieldsSequence(Form $form, Request $request){
        foreach($request->all() as $record){
            $field=FormField::find($record['id']);
            if($field->form_id==$record['form_id']){
                $field->sequence=$record['sequence'];
                $field->save();
            }
        }
        return redirect()->back();
    }
}