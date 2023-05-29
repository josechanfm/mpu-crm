<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Inquiry;
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
        return Inertia::render('Inquiry/Form',[
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
        // return redirect()->route('question',[
        //     'inquiry'=>$inquiry->id,
        //     'token'=>$inquiry->token
        // ]);
        //redirect()->route('inquiry.question',[$inquiry->id,$inquiry->token]);
        return response()->json($inquiry);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Inquiry $inquiry)
    {
        return redirect()->route('question',[
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
    public function question(Inquiry $inquiry,$token){
        if($inquiry->token!=$token){
            return response("false");
        }
        $subjects=json_decode($inquiry->subjects);
        $faqs=Faq::where(function($q) use($subjects){
            foreach($subjects as $s){
                $q->orWhereJsonContains('tags',$s);
            }
        })->get();
        return Inertia::render('Inquiry/Question',[
            'inquiry'=>$inquiry,
            'faqs'=>$faqs,
        ]);
    }
    public function getQuestion(){
        $faqs=Faq::all();
        return response()->json($faqs);
    }

}
