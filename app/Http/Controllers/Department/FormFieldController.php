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
        return Inertia::render('Department/Form/FormFields',[
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
        //$this->authorize('create',$form);
        $this->validate($request,[
            'form_id' => 'required',
            'field_label'=>'required',
            'type'=>'required',
        ]);
        $data=$request->all();
        $data['direction']=isset($request->direction)?$request->direction:'H';
        $data['required']=isset($request->required)?$request->required:false;
        $field['in_column']=isset($request->in_column)?$request->in_column:false;
        $form->fields()->create($data);
        return Inertia::location(route('manage.form.fields.index', $form->id));
        //return redirect()->back();

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
        //$this->authorize('update',$form);
        $this->validate($request,[
            'form_id' => 'required',
            'field_label'=>'required',
            'type'=>'required',
        ]);
        $data=$request->all();
        $data['direction']=isset($request->direction)?$request->direction:'H';
        $data['required']=isset($request->required)?$request->required:false;
        $field['in_column']=isset($request->in_column)?$request->in_column:false;

        FormField::find($request->id)->update($data);
        //return redirect()->back();
        return Inertia::location(route('manage.form.fields.index', $form->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department, Form $form, FormField $field)
    {

        // $formFieldNameArray=explode('_',$field->field_name);
        // $fields=FormField::where('form_id',$field->form_id)->where('field_name','like',$formFieldNameArray[0].'%')->get();
        // dd($field, $fields);
        // foreach($fields as $i=>$f){
        //     $arr=explode('_',$f->field_name);
        //     dd($f, $i, $arr);
        //     $field->fiele_name=$arr[0].'_'.$i.'_'.$arr[2];
        // }
        // dd($field, $formFieldNameArray, $fields);
        //dd($department, $form, $field);
        $field->delete();   
        return redirect()->back();
    }

    public function fieldsSequence(Form $form, Request $request)
    {
        foreach ($request->all() as $record) {
            $field = FormField::find($record['id']);
            if ($field->form_id == $record['form_id']) {
                $field->sequence = $record['sequence'];
                $field->save();
            }
        }
        return Inertia::location(route('manage.form.fields.index', $form->id));
        //.return redirect()->back();
    }

    public function clone(FormField $formField){
        $form=Form::find($formField->form_id);
        $fieldNameArray=explode('_',$formField->field_name);
        $newField=$formField->toArray();
        $newField['sequence']=$formField->sequence;
        if(sizeof($fieldNameArray)>1){
            $lastFieldName=FormField::where('form_id',$formField->form_id)->where('field_name','like',$fieldNameArray[0].'%')->orderBy('sequence','desc')->pluck('field_name')->first();
            $lastFieldNameArray=explode('_',$lastFieldName);
            $newField['field_name']=$fieldNameArray[0].'_'.($lastFieldNameArray[1]+1).'_'.$fieldNameArray[2];
        }else{
              $newField['field_name']=$newField['field_name'].'-'.time();
        }

        $newField['field_label']=$newField['field_label'].'(cloned)';

        $formField::create($newField);
        foreach($form->fields as $i=>$field){
            $field->sequence=$i;
            $field->save();
        }
        return Inertia::location(route('manage.form.fields.index', $form->id));
        //return redirect()->back();
        //return redirect()->back()->with(['fields'=>$form->fields]);
    }

   
}