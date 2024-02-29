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
        $this->authorizeResource(Department::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirect($roleName){
        $department=Department::where('abbr',$roleName)->first();
        if($department->default_route){
            return redirect()->route($department->default_route);
        }else{
            return redirect()->route('staff');
        }
    }
    public function index()
    {
        //$department=session('currentDepartment');
        $departments=AdminUser::find(Auth()->user()->id)->departments;
        if($departments->count()==1){
            return to_route('manage.departments.show',$departments[0]->id);
            //return redirect()->route('departments.show',$departments[0]->id);
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
        //session(['currentDepartmentId'=>$department->id]);
        session(['department'=>$department]);
        //dd(session('department'));

        $this->authorize('view',$department);

        return Inertia::render('Department/Dashboard',[
            'department'=>$department,
        ]);
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
