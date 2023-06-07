<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Config;
use App\Models\Inquiry;
use App\Models\Question;
use App\Models\Faq;

class InquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fields=Config::where('division','inquiry_form')->get()->toArray();
        $fields=array_column($fields,null,'label');
        $fields=array_map(function($field){
            return json_decode($field['value']);
        },$fields);
        return Inertia::render('Inquiry/Form',[
            'fields'=>Config::inquiryFormFields(),
            'lang'=>'zh',
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
        $inquiry=new Inquiry();
        $inquiry->department_id=1;
        $inquiry->lang=$request->lang;
        $inquiry->origin=$request->origin;
        $inquiry->degree=$request->degree;
        $inquiry->admission=$request->admission;
        $inquiry->profile=$request->profile;
        $inquiry->apply=$request->apply;
        $inquiry->surname=$request->surname;
        $inquiry->givenname=$request->givenname;
        $inquiry->email=$request->email;
        $inquiry->areacode=$request->areacode;
        $inquiry->phone=$request->phone;
        $inquiry->subjects=json_encode($request->subjects);
        $inquiry->token=hash('crc32',time().'mpu-crm');
        $inquiry->save();
        return to_route('inquiry.answerQuestion',[
            'inquiry'=>$inquiry->id,
            'token'=>$inquiry->token
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Inquiry $inquiry)
    {
        return redirect()->route('inquiry.question',[
            'inquiry'=>$inquiry->id,
            'token'=>$inquiry->token
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
    public function answerQuestion(Inquiry $inquiry,$token){
        if($inquiry->has_question){
            return to_route('inquiry.index');
        };
        if($inquiry->token!=$token){
            return to_route('inquiry.index');
        };
        if($inquiry->has_question){
            return to_route('inquiry.index');
        };
        // $inquiry->has_question=true;
        // $inquiry->save();
        $subjects=json_decode($inquiry->subjects);
        $faqs=Faq::getByTags($subjects);
        return Inertia::render('Inquiry/Question',[
            'inquiry'=>$inquiry,
            'faqs'=>$faqs,
        ]);
    }
    public function noQuestion(Inquiry $inquiry){
        $inquiry->has_question=false;
        $inquiry->save();
        return to_route('inquiry.thankQuestion');
    }

    public function submitQuestion(Inquiry $inquiry, Request $request){
        if($inquiry->token!=$request->token){
            return to_route('inquiry.index');
        };
        $inquiry->has_question=true;
        $inquiry->question=$request->content;
        $inquiry->save();
        if($request->file('fileList')){
            foreach($request->file('fileList') as $file){
                $inquiry->addMedia($file['originFileObj'])
                    ->toMediaCollection('questionAttachments');
            }
        }
        return to_route('inquiry.thankQuestion');
    }
    public function thankQuestion(){
        return Inertia::render('Inquiry/ThankQuestion',[

        ]);

    }

    public function getQuestion(){
        $faqs=Faq::all();
        return response()->json($faqs);
    }

}

