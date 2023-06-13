<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Form;
use App\Models\Response;
use App\Models\ResponseField;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth()->user()){
            $forms=Form::where('published',1)->get();
        }else{
            $forms=Form::where('published',1)->where('for_member',0)->get();
        }
        return Inertia::render('Forms/Form',[
            'forms'=>$forms
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
        //date('Y-m-d',strtotime($request->date))
            // $this->validate($request,[
            //     'form_id'=>'required',
            // ]);
                $response=new Response();
                $response->form_id=$request->form['id'];
                $response->member_id=Auth()->user()->id;
                $response->save();
                
                foreach($request->fields as $key=>$value){
                    $field=new ResponseField();
                    $field->response_id=$response->id;
                    $field->field_name=$key;
                    $field->field_value=$value;
                    $field->save();
                }
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
        $form=Form::with('fields')->find($id);
        if($form->require_member==1 && !Auth()->user()){
            return redirect('forms');
        }
        return Inertia::render('Forms/FormDefault',[
            'form'=>$form,
        ]);
        
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
    public function destroy($id)
    {
        //
    }
}
