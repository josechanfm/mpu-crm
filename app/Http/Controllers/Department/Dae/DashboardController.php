<?php

namespace App\Http\Controllers\Department\Dae;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Inertia\Inertia;
use App\Models\Souvenir;
use App\Models\SouvenirOrder;
use App\Models\SouvenirUser;
use App\Models\Department;

class DashboardController extends Controller
{
    public function index(){
        //dd(session('department'),auth()->user());
        // if(session('department')){
        session(['department' => Department::where('abbr','DAE')->first()]);
        
        // 检查统计缓存
        if (!session()->has('dae_stats')) {
            $stats = [
                'totalSouvenirs' => Souvenir::count(),
                'pendingOrders' => SouvenirOrder::where('status',0)->count(),
                'todayPickups' => SouvenirOrder::where('status', 2)->whereDate('updated_at', today())->count(),
                'totalUsers' => SouvenirUser::count()
            ];
            session(['dae_stats' => $stats]);
        }

        return Inertia::render('Department/Dae/Dashboard',[
            'department'=>session('department'),
            'stats' => session('dae_stats')
        ]);
        // }else{
        //     return redirect()->route('manage');
        // }
    }
}
