<?php

namespace App\Http\Controllers\Department\Dae;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\SouvenirPurchase;
use Inertia\Inertia;

class PickupController extends Controller
{
    public function index(){
        return Inertia::render('Department/Dae/Pickup',[
            'department'=>session('department')
        ]);
    }
    public function store(Request $request){
        dd($request->all());
    }
    public function getPurchase(Request $request){
        dd($request->all());
    }
}
