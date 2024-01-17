<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Mail\EnquiryEmail;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Config;
use App\Models\Department;
use App\Models\Enquiry;
use App\Models\EnquiryResponse;
use App\Models\EnquiryQuestion;
use Illuminate\Support\Facades\Mail;

class EnquiryQuestionController extends Controller
{
    public function __construct()
    {
        //$this->authorizeResource(EnquiryQuestion::class,'enquiryQuestion');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $department=session('department');
        
        $department->enquiryQuestionsOpen;
        $department->refresh();
        return Inertia::render('Department/Enquiry/Questions',[
            'department'=>$department,
            'fields'=>Config::enquiryFormFields(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Department $department, Request $request)
    {
        EnquiryQuestion::create($request->all());
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function show(EnquiryQuestion $question)
    public function show($id)
    {
        $enquiryQuestion=EnquiryQuestion::find($id);
        $this->authorize('view',$enquiryQuestion);
        dd($enquiryQuestion);

        $enquiry=Enquiry::with('questions')->find($enquiryQuestion->enquiry_id);
        $enquiryQuestion->enquiry->questions;
        return Inertia::render('Department/Enquiry/QuestionShow',[
            'department'=>$enquiryQuestion->enquiry->department,
            'fields'=>Config::enquiryFormFields(),
            'enquiry'=>$enquiry,
            'active_question'=>$enquiryQuestion->id,
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
        
        dd($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Department $department, Enquiry $enquiry, Request $request)
    {
        $enquiry->response=$request->response;
        $enquiry->response_date=date('Y-m-d H:i:s');
        $enquiry->save();
        return redirect()->back();
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
    public function response(Department $department, Request $request){
        $enquiryResponse = EnquiryResponse::create($request->all());

        if($request->file('fileList')){
            foreach($request->file('fileList') as $file){
                $enquiryResponse->addMedia($file['originFileObj'])
                    ->toMediaCollection('enquiryResponseAttachments');
            }
        };

        if($request->by_email){
            $email=[
                'id'=>$enquiryResponse->id,
                'address'=>$enquiryResponse->email_address,
                'title'=>$enquiryResponse->email_subject,
                'body'=>$enquiryResponse->email_content,
                'media'=>$enquiryResponse->media,
                'token'=>$enquiryResponse->token
            ];
            $this->sendEmail($email);
        }
        return redirect()->back();
    }

    public function sendEmail($email){
        Mail::send('emails.generalMail',$email, function($message) use($email){
            $message->to($email["address"])
                    ->subject($email["title"]);
            if(isset($email['media'])){
                foreach($email['media'] as $file){
                    $message->attach($file->getPath());
                }
            }
        });
    }

    
}
