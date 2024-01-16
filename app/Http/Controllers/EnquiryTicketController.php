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
        $response=EnquiryResponse::find($request->enquiry_response_id);

        if($response->is_used!==null || $response->token!=$request->token){
            return Inertia::render('Error',[
                'message'=>'The enquiry question already submit!'
            ]);
        }

        $enquiryQuestion=new EnquiryQuestion();
        $enquiryQuestion->enquiry_id=$response->question->enquiry_id;
        $enquiryQuestion->parent_id=$response->enquiry_question_id;        
        // $enquiryQuestion->enquiry_response_id=$response->id;
        $enquiryQuestion->content=$request->content;
        // $enquiryQuestion->token=Enquiry::token();
        $enquiryQuestion->is_closed=false;
        $enquiryQuestion->save();

        $response->is_used=true;
        $response->save();

        return Inertia::render('Enquiry/ThankQuestion',[

        ]);
    }
}
