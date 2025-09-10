<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Form;
use App\Models\Entry;
use App\Models\EntryRecord;
use PDF;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $forms=Form::where('published',1)->where('for_staff',0)->get();
        return Inertia::render('Form/Forms',[
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

        $entry=new Entry();
        $entry->form_id=$request->form['id'];
        //$entry->member_id=auth()->user()->id;
        $entry->save();

        foreach($request->fields as $key=>$value){
            $field=new EntryRecord();
            $field->entry_id=$entry->id;
            $field->form_field_id=$key;
            if(is_array($value)){
                $field->field_value=json_encode($value, JSON_UNESCAPED_UNICODE);
            }else{
                $field->field_value=$value;
            }
            $field->save();
        }
        $form=Form::find($entry->form_id);
        return Inertia::render('Form/Thanks',[
            'form'=>$form,
            'entry'=>$entry
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Form $form, Request $request)
    {
        //$form=Form::with('fields')->find($id);
        
        if(!$form->published && empty($request->view) && $request->view!=$form->uuid){
            return redirect()->route('forms.index');
        }
        $form->fields;
        if($form->require_login==1 && !Auth()->user()){
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

    public function thankYou(Entry $entry, Request $request){
        //dd($entry, $request->all());
        if($entry && $request->has('uuid') && $request->uuid==$entry->uuid){
            $form=Form::find($entry->form_id);
            return Inertia::render('Form/Thanks',[
                'form'=>$form,
                'entry'=>$entry
            ]);
        }
        return redirect()->route('forms.index');
    }

    public function receipt(Entry $entry, Request $request){
        if($entry && $request->has('uuid') && $request->uuid==$entry->uuid){
            //dd($entry->load(['form','records']));
            // return view('Form/EntryReceipt', [
            //     'entry' => $entry->load(['form','records']),
            // ]);

            $pdf = PDF::loadView('Form/EntryReceipt', [
                'entry' => $entry->load(['form','records']),
            ])
            ->setPaper('A4', 'portrait')
            ->setOption([
                'fontDir' => public_path('fonts/Noto'),
                'fontCache' => public_path('fonts'),
                'defaultFont' => 'NotoSansTC',
                'margin-top' => '20mm',    // Set top margin
                'margin-right' => '50mm',  // Set right margin
                'margin-bottom' => '20mm', // Set bottom margin
                'margin-left' => '15mm',   // Set left margin
            ]);

            //$pdf->render();
            
            return $pdf->stream('receipt.pdf', array('Attachment' => false));


        }
        return redirect()->route('forms.index');
    }

}
