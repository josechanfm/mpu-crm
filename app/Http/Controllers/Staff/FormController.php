<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Form;
use App\Models\Entry;
use App\Models\EntryRecord;

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
        //     $forms=Form::where('published',1)->where('for_staff',0)->get();
        // }
        $forms=Form::where('published',1)->where('for_staff',true)->orWhere('require_login',false)->get();
        return Inertia::render('Staff/Form/Forms',[
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

        // $entry=new Entry();
        // $entry->form_id=$request->form['id'];
        // $entry->admin_user_id=auth()->user()->id;
        // $entry->save();
        // foreach($request->fields as $key=>$value){
        //     $field=new EntryRecord();
        //     $field->entry_id=$entry->id;
        //     $field->form_field_id=$key;
        //     $field->field_value=$value;
        //     $field->save();
        // }
        // $form=Form::find($entry->form_id);
        // return Inertia::render('Staff/Form/Thanks',[
        //     'form'=>$form,
        //     'filled'=>$entry,
        // ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Form $form)
    {
        if(!$form->published){
            return redirect()->route('staff');
        }
        $form->fields;
        if($form->require_login==0 || $form->for_staff){
            $entry=$form->entries->where('admin_user_id',auth()->user()->id)->first();
            if($entry){
                return Inertia::render('Staff/Form/Duplicate',[
                    'form'=>$form,
                    'entry'=>$entry
                ]);
            }else if($form->layout){
                return Inertia::render('Form/'.$form->layout,[
                    'form'=>$form,
                    'entry'=>$entry
                ]);
            }else{
                return Inertia::render('Form/FormDefault',[
                    'form'=>$form,
                    'entry'=>$entry
                ]);
            }
        }

        //$form=Form::with('fields')->find($id);

        $form->fields;
        if($form->require_login==1 && !Auth()->user()){
            return redirect('forms');
        }
        return Inertia::render('Staff/Form/FormDefault',[
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
