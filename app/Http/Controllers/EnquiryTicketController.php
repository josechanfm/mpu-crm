<?php

namespace App\Http\Controllers;

use App\Models\EnquiryQuestion;
use Illuminate\Http\Request;
use App\Models\Enquiry;
use App\Models\EnquiryResponse;
use Inertia\Inertia;

class EnquiryTicketController extends Controller
{
    //
    public function ticket(EnquiryResponse $response, $token){
        if($response->is_used){
            return Inertia::render('Error',[
                'message'=>"<div class='flex justify-center items-center mt-10'><div><p>感謝您與澳理大聯繫，我們將儘快回覆。</p>
                <p>Thank you for contacting MPU, we'll respond to you shortly.</p>
                  <p><a href='www.mpu.edu.mo/admission'>www.mpu.edu.mo/admission</a></p></div></div>
                "
            ]);
        }
        if($response->token!=$token){
            return redirect('/');
        }
        return Inertia::render('Enquiry/Ticket',[
            'response'=>$response,
            'ticket'=>[
                'enquiry_response_id'=>$response->id,
                'content'=>'',
                'token'=>$response->token
            ]
        ]);
    }
    public function store(Request $request){
        //dd($request->all());
        $response=EnquiryResponse::find($request->enquiry_response_id);
        // $response->is_used=true;
        // $response->save();
        if($response->is_used==true || $response->token!=$request->token){
            return Inertia::render('Error',[
                'message'=>'The ticket number had beed used!'
            ]);
        }

        $enquiryQuestion=new EnquiryQuestion();
        $enquiryQuestion->enquiry_id=$response->question->enquiry_id;
        $enquiryQuestion->parent_id=$response->enquiry_question_id;        
        $enquiryQuestion->enquiry_response_id=$request->enquiry_response_id;
        $enquiryQuestion->content=$request->content;
        $enquiryQuestion->is_closed=false;
        $enquiryQuestion->save();

        $response->is_used=true;
        $response->save();

        if($request->file('fileList')){
            foreach($request->file('fileList') as $file){
                $enquiryQuestion->addMedia($file['originFileObj'])->toMediaCollection('enquiryQuestionAttachments');
            }
        };
        return Inertia::render('Enquiry/ThankQuestion',[
            'enquiryQuestion'=>$enquiryQuestion
        ]);

    }
}
