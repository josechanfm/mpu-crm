<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Config;
use App\Models\Department;
use App\Models\Inquiry;
use App\Models\Response;
use Mail;

class InquiryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Department::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Department $department)
    {
        $this->authorize('view',$department);
        return Inertia::render('Department/Inquiries',[
            'department'=>$department,
            'inquiries'=>$department->inquiries,
            'fields'=>Config::inquiryFormFields(),
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
    public function store(Department $department, Request $request)
    {
        $inquiry=new Inquiry();
        $inquiry->department_id=$request->department_id;
        $inquiry->parent_id=$request->parent_id;
        $inquiry->root_id=$request->root_id;
        $inquiry->email=$request->email;
        $inquiry->phone=$request->phone;
        //$inquiry->language=$request->language;
        //$inquiry->honorific=$request->honorific;
        $inquiry->name=$request->name;
        $inquiry->title=$request->title;
        $inquiry->content=$request->content;
        $inquiry->response=$request->response;
        $inquiry->admin_user_id=auth()->user()->id;
        $inquiry->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department, Inquiry $inquiry)
    {
        // $inquiry=Inquiry::where('id',1)->with('emails')->first();
        // dd($inquiry->department );

        //$inquiries=Inquiry::where('id',$inquiry->root_id)->with('children')->with('adminUser')->with('emails')->get();
        $inquiry=$inquiry->with('department')->with('responses')->first();
        // $inquiry->ssubjects=json_decode($inquiry->subjects,true);

        return Inertia::render('Department/InquiryShow',[
            'fields'=>Config::inquiryFormFields(),
            'inquiry'=>$inquiry
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
    public function update(Department $department, Inquiry $inquiry, Request $request)
    {
        $inquiry->response=$request->response;
        $inquiry->response_date=date('Y-m-d H:i:s');
        $inquiry->save();
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
        $response = new Response();
        $response->inquiry_id=$request->inquiry_id;
        $response->title=$request->title;
        $response->remark=$request->remark;
        $response->by_email=$request->by_email;
        $response->email_address=$request->email_address;
        $response->email_subject=$request->email_subject;
        $response->email_content=$request->email_content;
        $response->admin_id=auth()->user()->id;
        $response->save();

        if($request->file('fileList')){
            foreach($request->file('fileList') as $file){
                $response->addMedia($file['originFileObj'])
                    ->toMediaCollection('responseAttachments');
            }
        };
        if($request->by_email){
            $email=[
                'address'=>$response->email_address,
                'title'=>$response->email_subject,
                'body'=>$response->email_content,
                'media'=>$response->media
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
                    $message->attach(public_path('media/'.$file->id.'/'.$file->file_name));
                }
            }
        });
    }

    
}
