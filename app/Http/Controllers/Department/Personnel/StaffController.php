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

        $this->refreshStaffRecords();


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

    public function refreshStaffRecords()
    {
        $data = Staff::get_remote_data('list', 'super');
        foreach ($data as $item) {
            // Assuming $item is now an associative array
            $staff = Staff::updateOrCreate(
                ['staff_num' => $item['staff_num'], 'username' => $item['netid']],
                [
                    'name_zh' => $item['name_zh'], 
                    'name_pt' => $item['name_pt'], 
                    'email' => $item['email'], 
                    'staff_num' => $item['staff_num'],
                    'phone' => $item['office_tel'],
                    'fullpart' => $item['fullpart'],
                    'cat_group' => $item['cat_group'],
                    'lecturer' => $item['lecturer'],
                    'medical_num' => $item['medical_num'],
                    'medical_type' => $item['medical_type'],
                    'library_num' => $item['library_num'],
                    'register_date' => $item['register_date'],
                    'dept' => $item['dept_code'],
                    //'register_date' => $item['register_date']
                ]
            );
        }
        return count($data);
    }
}
