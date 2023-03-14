<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Form;

class FormController extends Controller
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
    public function index(Organization $organization)
    {
        $this->authorize('view',$organization);
        return Inertia::render('Organization/Form',[
            'organization'=>$organization,
            'forms'=>$organization->forms
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Organization $organization)
    {
        return Inertia::render('Organization/FormEdit',[
            'form'=>new Form()
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
        $organization = Organization::find($request->organization_id);

        if($organization->hasUser(Auth()->user())){
            $this->validate($request,[
                'organization_id' => 'required',
                'name'=>'required',
                'title'=>'required',
            ]);
            $form=new Form();
            $form->organization_id=$request->organization_id;
            $form->name=$request->name;
            $form->title=$request->title;
            $form->description=$request->description;
            $form->with_login=$request->with_login;
            $form->with_member=$request->with_member;
            $form->save();
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization, Form $form)
    {
        // $this->authorize('view',$organization);
        // $this->authorize('view',$form);

        // echo 'edit form';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Organization $organization, Form $form)
    {
        //$this->authorize('update',$organization);
        //$this->authorize('update',$form);
        // return Inertia::render('Organization/FormEdit',[
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization, Form $form)
    {
        if($form->hasChild()){
            return redirect()->back()->withErrors(['message'=>'No permission or restriced deletion of records with child records.']);
        }else{
            $form->delete();
            return redirect()->back();
        }
        
    }
}
