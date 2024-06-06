<?php

namespace App\Http\Controllers\Department\Personnel\Recruitment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Config;
use App\Models\Department;
use App\Models\RecTask;
use App\Models\RecWorkflow;
use Illuminate\Queue\Worker;

class WorkflowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Department/Personnel/Recruitment/Workflows',[
            'departments'=>Department::all(),
            'workflows'=>RecWorkflow::all()
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Department/Personnel/Recruitment/Workflow',[
            'departments'=>Department::all(),
            'workflowCategories'=>Config::item('workflow_categories')->value,
            'procedureCategories'=>Config::item('procedure_categories')->value,
            'workflow'=>RecWorkflow::make(['status'=>'ARCHIVE']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$workflow=RecWorkflow::create($request->all());
        //dd($workflow);
        $workflow=RecWorkflow::find(305);
        dd($workflow);
        $tasks=RecTask::select('name','sequence','department_id','days','email')->where('procedure_code','ACA')->get();
        foreach($tasks as $task){
            $task['active']=true;
            dd($task);
            $workflow->activities()->create($tasks);
        }
        //dd($tasks);
        

        dd($tasks);
        return redirect()->back();
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
    public function edit(RecWorkflow $workflow)
    {
        return Inertia::render('Department/Personnel/Recruitment/Workflow',[
            'departments'=>Department::all(),
            'workflowCategories'=>Config::item('workflow_categories')->value,
            'procedureCategories'=>Config::item('procedure_categories')->value,
            'workflow'=>$workflow,
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
