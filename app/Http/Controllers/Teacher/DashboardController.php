<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Teacher;
use App\Models\Offer;

class DashboardController extends Controller
{
    public function index()
    {
        // dd("teacher!");
        // $teacher=Teacher::where('user_id',auth()->user()->id)->with('offers')->first();
        return Inertia::render('Teacher/Dashboard');
    }
    
}
