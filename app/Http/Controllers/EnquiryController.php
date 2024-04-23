<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Config;
use App\Models\Enquiry;
use App\Models\Department;
use App\Models\EnquiryQuestion;
use App\Models\Faq;
use Illuminate\Support\Facades\Storage;


class EnquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fields=Config::enquiryFormFields();
        //dd($fields);
        if(!isset($fields) && sizeof($fields)==0){
            return Inertia::render('Error',[
                'message'=>'Enquiry Config missing or data corrupted!'
            ]);
        };
        return Inertia::render('Enquiry/Form',[
            'fields2'=>$fields,
            'phone_country_codes'=>Config::item('phone_country_codes'),
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
        $data=$request->all();
        $department=Department::where('abbr','DAMIA')->first();
        if($department){
            $data['department_id']=$department->id;
        }
        $enquiry=Enquiry::create($data);
        return to_route('enquiry.answerQuestion',[
            'enquiry'=>$enquiry->id,
            'token'=>$enquiry->token
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Enquiry $enquiry)
    {
        return redirect()->route('enquiry.question',[
            'enquiry'=>$enquiry->id,
            'token'=>$enquiry->token
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
    public function answerQuestion(Enquiry $enquiry,$token){
        if($enquiry->has_question){
            return to_route('enquiry.index');
        };
        if($enquiry->token!=$token){
            return to_route('enquiry.index');
        };
        if($enquiry->has_question!==null){
            return Inertia::render('Error',[
                'message'=>'The enquiry question already submit!'
            ]);
        };
        $faqs=Faq::getByEnquiry($enquiry);
        return Inertia::render('Enquiry/AnswerQuestion',[
            'enquiry'=>$enquiry,
            'faqs'=>$faqs,
        ]);
    }
    public function noQuestion(Enquiry $enquiry){
        if($enquiry->has_question!==null){
            return Inertia::render('Error',[
                'message'=>'The enquiry question already submit!'
            ]);
        }
        $enquiry->has_question=false;
        $enquiry->save();
        //return to_route('enquiry.thankQuestion');
        return to_route('enquiry.thankQuestion');
    }

    public function submitQuestion(Enquiry $enquiry, Request $request){
        if($enquiry->token!=$request->token){
            return to_route('enquiry.index');
        };
        if($enquiry->has_question!==null){
            return Inertia::render('Error',[
                'message'=>'The enquiry question already submit!'
            ]);
        }
        //  dd($request->file());
        $enquiry->has_question=true;
        $enquiry->save();
        $data=$request->all();
        $data['enquiry_id']=$enquiry->id;
        $enquiryQuestion=EnquiryQuestion::create($data);

        if($request->file('fileList')){
            foreach($request->file('fileList') as $file){
                $enquiryQuestion->addMedia($file['originFileObj'])->toMediaCollection('enquiryQuestionAttachments');
            }
        };
        //return $this->thankQuestion($enquiryQuestion);
        return to_route('enquiry.thankQuestion',['eq'=>$enquiryQuestion->id,'t'=>$enquiry->token]);
    }
    public function thankQuestion(Request $request){
        //dd($request->enquiry_id);
        if($request->eq){
            $enquiryQuestion=EnquiryQuestion::find($request->eq);
            if($enquiryQuestion->enquiry->token!==$request->t){
                return Inertia::render('Error',[
                    'message'=>'Request prohibited!'
                ]);
    
            }
        }else{
            $enquiryQuestion=null;
        };
        
        return Inertia::render('Enquiry/ThankQuestion',[
            'enquiryQuestion'=>$enquiryQuestion
        ]);

    }

    public function faqs(){
        return Inertia::render('Enquiry/Faqs',[
            'faqs'=>Faq::all()
        ]);
    }

}

