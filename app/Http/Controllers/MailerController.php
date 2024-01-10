<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Email;
use App\Models\Department;
use App\Mail\EnquiryEmail;
use App\Models\Enquiry;
use Notification;
//use Mail;
use Illuminate\Support\Facades\Mail;

class MailerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $emails=Email::where('admin_user_id',auth()->user()->id)->with('media')->get();
        //dd($emails);
        return Inertia::render('Mailer/MailList',[
            'department'=>Department::find(1),
            'emails'=>$emails
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
        // return response()->json($request->all());
        if(isset($request->id)){
            $email=Email::find($request->id);
            Mail::to($email->receiver)->send(new EnquiryEmail($email));
            return response()->json($request->all());
        };
        $email=new Email();
        $email->admin_user_id=auth()->user()->id;
        $email->sender=$request->sender;
        $email->receiver=$request->receiver;
        $email->subject=$request->subject;
        $email->content=$request->content;
        $email->save();
        if($request->file('attachments')){
            foreach($request->attachments as $file){
                $email->addMedia($file['originFileObj'])->setFileName($email->id.'_'.$file['uid'].'.'.substr(strrchr($file['name'], '.'), 1))->toMediaCollection('emailAttachments');
            }
        }
        Mail::to($email->receiver)->send(new EnquiryEmail($email));
        return response()->json($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
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
