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
                'message'=>'The enquiry question already submit!'
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
