<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Form;
use App\Models\FormField;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FormFieldController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Organization::class);
        $this->authorizeResource(Form::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Organization $organization, Form $form)
    {
        return Inertia::render('Organization/FormField',[
            'organization' => $organization,
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
    public function store(Organization $organization, Form $form, Request $request)
    {
        $this->validate($request,[
            'form_id' => 'required',
            'field_name'=>'required',
            'field_label'=>'required',
        ]);

        if($organization->hasUser(Auth()->user())){
            $field=new FormField();
            $field->form_id=$request->form_id;
            $field->field_name=$request->field_name;
            $field->field_label=$request->field_label;
            $field->type=$request->type;
            $field->required=$request->required;
            $field->rule=$request->rule;
            $field->validate=$request->validate;
            $field->remark=$request->remark;
            $field->save();
            return redirect()->back();
        }

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
    public function update(Organization $organization, Form $form, Request $request )
    {
        $this->validate($request,[
            'form_id' => 'required',
            'field_name'=>'required',
            'field_label'=>'required',
        ]);
        if($organization->hasUser(Auth()->user())){
            $field=FormField::find($form->id);
            $field->form_id=$request->form_id;
            $field->field_name=$request->field_name;
            $field->field_label=$request->field_label;
            $field->type=$request->type;
            $field->required=$request->required;
            $field->rule=$request->rule;
            $field->validate=$request->validate;
            $field->remark=$request->remark;
            $field->save();
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization, Form $form, FormField $field)
    {
        $field->delete();        
    }
}
