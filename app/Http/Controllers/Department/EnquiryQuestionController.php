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
        //$this->authorizeResource(Department::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$department=Department::find(session('currentDepartmentId'));
        $department=session('department');
        //$this->authorize('view',$department);
        $department->enquiryQuestionsOpen;
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
        $enquiryResponse=EnquiryResponse::find(17);
        $enquiryResponse->media;
        dd($enquiryResponse->media[0]->getPath());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Department $department, Request $request)
    {
        $enquiry=new Enquiry();
        $enquiry->department_id=$request->department_id;
        $enquiry->parent_id=$request->parent_id;
        $enquiry->root_id=$request->root_id;
        $enquiry->email=$request->email;
        $enquiry->phone=$request->phone;
        //$enquiry->language=$request->language;
        //$enquiry->honorific=$request->honorific;
        $enquiry->name=$request->name;
        $enquiry->title=$request->title;
        $enquiry->content=$request->content;
        $enquiry->response=$request->response;
        $enquiry->admin_user_id=auth()->user()->id;
        $enquiry->token=hash('crc32',time().'1');
        $enquiry->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(EnquiryQuestion $question)
    {
        // $this->authorize('view',$enquiry->department);
        $this->authorize('view',$question->enquiry->department);
        $enquiry=Enquiry::with('questions')->find($question->enquiry_id);
        $question->enquiry->questions;
        return Inertia::render('Department/Enquiry/QuestionShow',[
            'department'=>$question->enquiry->department,
            'fields'=>Config::enquiryFormFields(),
            'enquiry'=>$enquiry,
            'active_question'=>$question->id,
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
                'address'=>$enquiryResponse->email_address,
                'title'=>$enquiryResponse->email_subject,
                'body'=>$enquiryResponse->email_content,
                'media'=>$enquiryResponse->media,
                'token'=>$enquiryResponse->token
                ];
                $this->sendEmail($email);
        }
        
        // $enquiryQuestion=EnquiryQuestion::find($enquiryResponse->enquiry_question_id);
        // $enquiryQuestion->is_closed=isset($request->is_closed)?$request->is_closed:0;
        // $enquiryQuestion->save();
        
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
