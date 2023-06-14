<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Form;
use App\Models\Filled;
use App\Models\FilledField;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if(Auth()->user()){
        //     $forms=Form::where('published',1)->get();
        // }else{
        //     $forms=Form::where('published',1)->where('for_member',0)->get();
        // }
        $forms=Form::where('published',1)->where('for_member',0)->get();
        return Inertia::render('Form/Form',[
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
                $filled=new Filled();
                $filled->form_id=$request->form['id'];
                //$filled->member_id=auth()->user()->id;
                $filled->save();
                foreach($request->fields as $key=>$value){
                    $field=new FilledField();
                    $field->filled_id=$filled->id;
                    $field->form_field_id=$key;
                    $field->field_value=$value;
                    $field->save();
                }
                $form=Form::find($filled->form_id);
                return Inertia::render('Form/Thanks',[
                    'form'=>$form,
                    'filled'=>$filled,
                ]);
        
                //return redirect()->route('form.thanks',$filled);
                //return to_route('form.thanks', ['form'=>$request->form['id']]);
                //return to_route('form.thanks',$request->form['id']);
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
        return Inertia::render('Form/FormDefault',[
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
