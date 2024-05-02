<?php

namespace App\Http\Controllers\Department\Registry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Config;
use App\Models\Department;
use App\Models\Faq;

class FaqController extends Controller
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
        $department=Department::where('abbr','DAMIA')->with('faqs')->first();
        return Inertia::render('Department/Registry/Faqs',[
            'department'=>$department,
            'fields'=>Config::enquiryFormFields()
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
        $department=Department::find($request->department_id);
        //$this->authorize('create',$department);
        Faq::create($request->all());
        // $faq=new Faq;
        // $faq->department_id=$request->department_id;
        // $faq->category_id=$request->category_id;
        // $faq->degree=$request->degree;
        // $faq->subjects=$request->subjects;
        // $faq->question_zh=$request->question_zh;    
        // $faq->answer_zh=$request->answer_zh;
        // $faq->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department, Faq $faq)
    {
        dd($faq);
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
    public function update(Faq $faq, Request $request)
    {
        //$this->authorize('create',$faq->department);
        $faq->update($request->all());
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

