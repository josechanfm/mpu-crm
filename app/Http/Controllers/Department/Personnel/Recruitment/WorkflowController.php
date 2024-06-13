<?php

namespace App\Http\Controllers\Department\Personnel\Recruitment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Config;
use App\Models\Department;
use App\Models\RecActivity;
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
            'workflows'=>RecWorkflow::orderBy('date_start','DESC')->paginate()
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
            'vacancyTypes'=>Config::item('vacancy_types')->value,
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
        $workflow=RecWorkflow::create($request->all());
        $tasks=RecTask::select('name','sequence','department_id','days','email','remark')->where('vacancy_type',$workflow->vacancy_type)->orderBy('sequence')->get()->toArray();
        foreach($tasks as $i=>$task){
            $activities[$i]['rec_workflow_id']=$workflow->id;
            $activities[$i]['sequence']=$task['sequence'];
            $activities[$i]['department_id']=$task['department_id'];
            $activities[$i]['vacancy_code']=$request->vacancy_code;
            $activities[$i]['name']=$task['name'];
            $activities[$i]['days']=$task['days'];
            $activities[$i]['remark']=$task['remark'];
            if($i==0){
                $activities[$i]['date_start']=$request->date_start;
                $activities[$i]['date_end']=date('Y-m-d',strtotime($request->date_start.' +'.$task['days'].' days'));
            }else{
                $activities[$i]['date_start']=date('Y-m-d',strtotime($activities[$i-1]['date_end'].' +1 days'));
                $activities[$i]['date_end']=date('Y-m-d',strtotime($activities[$i]['date_start'].' +'.$task['days'].' days'));
            }
            $activities[$i]['target_start']=$activities[$i]['date_start'];
            $activities[$i]['target_end']=$activities[$i]['date_end'];
            $activities[$i]['active']=true;
        }
        RecActivity::upsert(
            $activities,
            ['rec_workflow_id','sequence'],
            ['department_id','sequence','name','days','date_start','date_end','target_start','target_end','remark']
        );
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
            'vacancyTypes'=>Config::item('vacancy_types')->value,
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
    public function update(Request $request, RecWorkflow $workflow)
    {
        $workflow->update($request->all());
        return to_route('personnel.recruitment.workflows.index');
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
