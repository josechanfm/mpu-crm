<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Scheduler;

class SchedulerController extends Controller
{
    public function index(Request $request){
        return inertia('Master/Schedulers',[
            'schedulers'=>Scheduler::paginate($request->per_page)
        ]);
    }
}
