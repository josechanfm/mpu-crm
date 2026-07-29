<?php

namespace App\Http\Controllers\Department\Registry;

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
    public function index(Request $request)
    {
        $department = Department::where('abbr', 'DAMIA')->first();
        
        if ($department) {
            $questions = EnquiryQuestion::with('lastResponse')
                ->with('enquiry')
                ->join('enquiries', 'enquiry_questions.enquiry_id', '=', 'enquiries.id')
                ->leftJoin('admin_users', 'enquiry_questions.admin_id', '=', 'admin_users.id') // For sorting by admin_user
                ->where('enquiries.department_id', $department->id)
                ->select('enquiry_questions.*');
        } else {
            $questions = collect();
        }
        
        // Apply filters if they exist
        if ($request->has('filters')) {
            $filters = $request->filters;
            if (!empty($filters['status'])) {
                $questions->where('enquiry_questions.is_closed', $filters['status'][0] == 'true' ? 1 : 0);
            }
        } else {
            // Both page and filters are not set, default to open questions
            if (!$request->has('page')) {
                $questions->where('enquiry_questions.is_closed', 0);
            }
        }

        // Apply sorting
        if ($request->has('sort_field') && !is_null($request->sort_field) && $request->has('sort_order')) {
            $sortField = $request->input('sort_field');
            $sortOrder = $request->input('sort_order');
            if ($sortOrder === 'ascend') {
                $sortOrder = 'asc';
            } elseif ($sortOrder === 'descend') {
                $sortOrder = 'desc';
            }
            // Validate sort order
            if (!in_array($sortOrder, ['asc', 'desc'])) {
                $sortOrder = 'asc';
            }
            
            // Map frontend column names to database columns if needed
            $sortFieldMap = [
                'enquiries.id' => 'enquiries.id',
                'created_at' => 'enquiry_questions.created_at',
                // 'enquiries.origin' => 'enquiries.origin',
                // 'enquiries.admission' => 'enquiries.admission',
                // 'enquiries.degree' => 'enquiries.degree',
                // 'enquiries.surname' => 'enquiries.surname',
                // 'admin_users.name' => 'admin_users.name',
                // 'enquiry_questions.is_closed' => 'enquiry_questions.is_closed'
            ];
            //dd($sortField, $sortFieldMap, $sortFieldMap[$sortField]);
            // Use mapped field or default to the provided field
            $sortField = $sortFieldMap[$sortField] ?? $sortField;
            
            
            // Apply the sort
            $questions->orderBy($sortField, $sortOrder);
        }

        // Get per_page from request or use default
        $perPage = $request->input('per_page', 10);
        
        // Validate per_page to ensure it's one of the allowed values
        $allowedPerPage = [10, 25, 50, 75, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }
        
        
        return Inertia::render('Department/Registry/Questions', [
            'questions' => $questions->paginate($perPage),
            'department' => $department,
            'configFields' => Config::enquiryFormFields(),
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
        $department=$enquiryQuestion->enquiry->department;

        $enquiry=Enquiry::with('questions')->find($enquiryQuestion->enquiry_id);
        $enquiry->questions;
        //$enquiryQuestion->enquiry->questions;
        //dd($enquiry);
        //dd($enquiryQuestion->enquiry->questions[0]->media[0]->original_url);
        return Inertia::render('Department/Registry/QuestionShow',[
            'department'=>$department,
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
        $question=EnquiryQuestion::find($request->enquiry_question_id);
        if(isset($request->is_closed) && $request->is_closed){
            $closeDate=date('Y-m-d h:i:s');
            $question->is_closed=true;
            $question->closed_at=$closeDate;
            $question->day_used=abs(floor((strtotime($closeDate) - strtotime($question->created_at)) / 60 / 60 /24) );
        }else{
            $question->is_closed=false;
            $question->closed_at=null;
            $question->day_used=null;
        }
        $question->save();
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
        Mail::send('emails.enquiryResponseMail',$email, function($message) use($email){
            $message->from('no-reply@mpu.edu.mo','MPU Admission')
                    ->to($email["address"])
                    ->subject($email["title"]);
            if(isset($email['media'])){
                foreach($email['media'] as $file){
                    $message->attach($file->getPath());
                }
            }
        });
    }

    
}
