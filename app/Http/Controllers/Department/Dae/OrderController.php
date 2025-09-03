<?php

namespace App\Http\Controllers\Department\Dae;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\SouvenirOrder;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //  dd($request->all());
         $orders = SouvenirOrder::query();
        if ($request->has('filter_column') && !is_null($request->filter_column && $request->has('filter_value') && !is_null($request->filter_value))) {
            if($request->filter_value=='null'){
                $orders = SouvenirOrder::whereNull($request->filter_column);
            }else{
                $orders = SouvenirOrder::where($request->filter_column, $request->filter_value);
            }
        }else{
            $orders = SouvenirOrder::where('status', config('constants.ORDER_PAID'));
        }
        if($request->search_column && $request->search_column=='buyer' && $request->search_text){
            $orders = $orders->whereHas('user', function($query) use ($request){
                $query->where('netid','LIKE', '%'.$request->search_text.'%');
            });
        }
        // if ($request->has('search_column') && !is_null($request->search_column) && $request->has('search_text') && !is_null($request->search_text)) {
        //     $orders = $souvenior->where($request->search_column,'LIKE', '%'.$request->search_text.'%');
        // }
        // if ($request->has('show_all') && $request->show_all && $request->show_all == 'true') {
        // }else{
        //    $orders = $orders->where('is_available', true);
        // }
        return Inertia::render('Department/Dae/Orders',[
            "orders"=>$orders->with('user')->paginate($request->per_page??5),
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
