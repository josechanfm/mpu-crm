<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminUser;
use App\Models\Department;
use Inertia\Inertia;

class DepartmentController extends Controller
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
        if(auth()->user()->hasRole('admin')){
            $departments=Department::all();
        }else{
            $departments=AdminUser::find(Auth()->user()->id)->departments;
        }
        if($departments->count()==1){
            return redirect()->route('departments.show',$departments[0]->id);
        }elseif($departments->count()>1){
            return Inertia::render('Department/Selection',[
                'departments' => $departments
            ]);
        }else{
            echo '!!';
        }

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        dd($department);
        // return Inertia::render('Department/Dashboard',[
        //     'department'=>$department
        // ]);
        //return redirect(route('department.certificates.index',[$department->id]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {         

        //$this->authorize('update' , $department);


        return Inertia::render('Department/DepartmentEdit',[
            'department'=>$department,
        ]);
        
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
