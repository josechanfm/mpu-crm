<?php

namespace App\Http\Controllers\Department\Personnel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Department/Personnel/Staffs',[
            'staffs'=>Staff::paginate($request->get('per_page',10)),
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
     * @param  int  $i d
     * @return \Illuminate\Http\Response
     */
    public function show(Staff $staff)
    {

        //$data=json_decode(Staff::get_remote_data('list', 'super'));
        $data='{
            "family":[
                {
                    "has_allowance":1,
                    "has_medical":1,
                    "medical_num":"MPU/1199/F",
                    "staff_num":"1199",
                    "name_zh":"謝竣揚",
                    "name_pt":"CHE CHON IEONG",
                    "relationship":"親生子女",
                    "allowance_type":"卑親屬",
                    "dob":"24-09-2021",
                    "id_num":"16966517",
                    "medical_type":"A"
                },
                {
                    "has_allowance":1,
                    "has_medical":1,
                    "medical_num":"MPU/1199/F",
                    "staff_num":"1199",
                    "name_zh":"謝竣丞",
                    "name_pt":"CHE CHON SENG",
                    "relationship":"親生子女",
                    "allowance_type":"卑親屬",
                    "dob":"28-05-2013",
                    "id_num":"15747827",
                    "medical_type":"A"
                },
                {
                    "has_allowance":1,
                    "has_medical":1,
                    "medical_num":"MPU/1199/F",
                    "staff_num":"1199",
                    "name_zh":"鄭敬棠",
                    "name_pt":"CHIANG KENG TONG",
                    "relationship":"父母",
                    "allowance_type":"尊親屬",
                    "dob":"15-04-1955",
                    "id_num":"72822117",
                    "medical_type":"A"
                },
                {
                    "has_allowance":1,
                    "has_medical":1,
                    "medical_num":"MPU/1199/F",
                    "staff_num":"1199",
                    "name_zh":"黃少梅",
                    "name_pt":"WONG SIO MUI",
                    "relationship":"父母",
                    "allowance_type":"尊親屬",
                    "dob":"16-11-1960",
                    "id_num":"73319980",
                    "medical_type":"A"
                }
            ]
        }';
        dd('staff data',$data, json_decode($data)->family);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Staff $staff)
    {
        $data=Staff::get_remote_data('family', 'super', $staff->staff_num);
        dd($data, $data->family);

        return inertia('Department/Personnel/StaffEdit',[
            'staff'=>$staff->load('uploads'),
            'families'=>$data->family
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
