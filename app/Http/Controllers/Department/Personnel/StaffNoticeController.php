<?php

namespace App\Http\Controllers\Department\Personnel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StaffNotice;

class StaffNoticeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $notices=StaffNotice::with(['staff:staffs.id,staffs.staff_num,staffs.name_zh,staffs.name_pt','relative:staff_relatives.id,staff_relatives.name_zh,staff_relatives.name_pt']);
        if($request->has('search_field') && $request->has('search_value') && $request->search_field!=null && $request->search_value!=null){
            $notices->where($request->search_field, 'like', '%'.$request->search_value.'%');
        }
        //dd($request->all(), $notices->orderBy('date','asc')->paginate($request->get('per_page',$request->per_page)));

        if($request->has('filter')){
            if($request->filter!='ALL'){
                $notices->where('status',$request->filter);
            }
        };
        //dd($notices->orderBy('date','asc')->paginate(0));
        //dd($notices->paginate($request->get('per_page',$request->per_page)));
        return inertia('Department/Personnel/StaffNotices',[
            'notices'=>$notices->orderBy('date','asc')->paginate($request->get('per_page',$request->per_page))
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
