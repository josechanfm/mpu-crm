<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Form;
use Inertia\Inertia;

class FilledController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Form $form)
    {
        $department=Department::find(session('currentDepartmentId'));
        $form=Form::with('fields')->find($form->id);
        $filleds=$form->filleds;
        $columns=[(object)['title'=>'Nubmer','dataIndex'=>'uid']];
        foreach($form->fields as $field){
            if($field->in_column){
                $columns[]=(object)['title'=>ucfirst($field->field_name),'dataIndex'=>$field->field_name];
                foreach($filleds as $k=>$filled){
                    foreach($filled->fields as $ff){
                        if($ff->field_name==$field->field_name){
                            $filleds[$k][$field->field_name]=$ff->field_value;
                        }
                    }
                    
                }
            }
        }
        $columns[]=(object)['title'=>'Submit at','dataIndex'=>'created_at'];
        $columns[]=(object)['title'=>'Action','dataIndex'=>'operation'];

        return Inertia::render('Department/Filleds',[
            'department'=>$department,
            'form'=>$form,
            'filleds'=>$filleds,
            'columns'=>$columns
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
