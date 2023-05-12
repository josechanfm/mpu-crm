<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Department;
use App\Models\Inquiry;

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
        $inquiries=$department->inquiries;
        // $inquiries=Inquiry::whereBelongsTo($department)
        //     ->whereLike(['title','content','response'],'語言')
        //     ->get();
        return Inertia::render('Department/Inquiries',[
            'department'=>$department,
            'inquiries'=>$department->inquiries
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
        // $inquiry=Inquiry::where('id',1)->with('emails')->get();
        // dd($inquiry);

        $inquiries=Inquiry::where('id',$inquiry->root_id)->with('children')->with('adminUser')->with('emails')->get();
        return Inertia::render('Department/InquiryShow',[
            'department'=>$department,
            'inquiries'=>$inquiries,
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
}
