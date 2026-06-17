<?php

namespace App\Http\Controllers\Department\Saf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\SouvenirOrder;

class PaymentController extends Controller
{
    public function index(Request $request){
        return Inertia::render('Department/Saf/Payment/Home',[
            'department'=>session('department')
        ]);
    }
    public function souvenirOrders(Request $request){
        //dd('SouvenirOrders');
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
        return Inertia::render('Department/Saf/Payment/DaeReport',[
            'department'=>session('department'),
            "orders"=>$orders->orderBy('id','desc')->with('user')->paginate($request->per_page??5),
        ]);
    }

    public function souvenirExport(Request $request){
        dd('Souvenir Export');
    }
}
