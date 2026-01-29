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
        //$forms=Form::where('published',true)->where('for_staff',true)->orWhere('require_login',false)->get();
        $forms=Form::where('published',true)->orWhere('for_staff',true)->get();
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
    public function show(Form $form, Request $request)
    {
        return redirect()->route('forms.show', [
            'form' => $form->id,
            'user_id' => auth()->user()->id,
            'uuid' => $form->uuid
        ]);


        //$form=Form::with('fields')->find($id);
        if(!$form->published && empty($request->view) && $request->view!=$form->uuid){
            return redirect()->route('forms.index');
        }
        $form->fields;
        if($form->require_login==1 && !auth()->user()){
            return redirect('forms');
        }
        $form->banner=$form->media()->where('collection_name','banner')->first()?->original_url;
        $form->thumbnail==$form->media()->where('collection_name','thumbnail')->first()?->original_url;
        if($form->layout){
            /* Groupping for only one field, required setup manually from databases*/
            $grouping=$form->fields()->where('grouping',true)->first();
            if($grouping){
                $form->entry_groups=$form->entries_group_count($grouping->field_name);
            }
            return Inertia::render('Form/'.$form->layout,[
                'form'=>$form,
            ]);
        }else{
            return Inertia::render('Form/FormDefault',[
                'form'=>$form,
            ]);
        }
        
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
