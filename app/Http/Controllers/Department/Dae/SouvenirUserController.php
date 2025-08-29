<?php

namespace App\Http\Controllers\Department\Dae;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\SouvenirUser;

class SouvenirUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //dd($request->all());
        $users = SouvenirUser::query();
        if ($request->has('filter_column') && !is_null($request->filter_column && $request->has('filter_value') && !is_null($request->filter_value))) {
            $users = $users->where($request->filter_column, $request->filter_value);
        }else{
            $users = $users->where('can_buy', true);
        }
        if($request->search_column && $request->search_column=='buyer' && $request->search_text){
            $users = $users->where('netid', 'LIKE', '%'.$request->search_text.'%');
        }

        return Inertia::render('Department/Dae/SouvenirUsers',[
            'users' => $users->paginate()
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
