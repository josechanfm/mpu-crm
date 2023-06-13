<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Email;
use App\Models\Department;
use App\Models\Enquiry;
use App\Notifications\EnquiryResponseNotification;
use Notification;

class NotificationMailerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $emails=Email::where('admin_user_id',auth()->user()->id)->with('media')->get();
        return Inertia::render('Notification/MailList',[
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
        //return response()->json($request->attachments);
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
        $this->sendEmail($email);
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

    public function sendEmail($email){
        $receiver=['email'=>'josechan@mpu.edu.mo'];
        $response=[
            'greeting'=>'Dr. xxx',
            'body'=>$email->content,
            'thanks'=>'Thank you this is from mpu.edu.mo',
            'cc'=>[],//$email->cc, //preg_split('/ (@|vs) /', $input);
            'actionText'=>'',
            'actionUrl'=>'',
            'id'=>'',
            // 'actionText'=>'View this',
            // 'actionUrl'=>url('https://www.mpu.edu.mo'),
            // 'id'=>100,
            'footer'=>'mpu all right reserved...'
        ];
        Notification::route('mail','josechan@ipm.edu.mo')->notify(new EnquiryResponseNotification($response));

    }
}
