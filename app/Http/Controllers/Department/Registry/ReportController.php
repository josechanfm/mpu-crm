<?php

namespace App\Http\Controllers\Department\Registry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\EnquiryQuestion;

class ReportController extends Controller
{
    public function index(){

        return Inertia::render('Department/Registry/Report/Dashboard',[
            'enquiryQuestionStat'=>EnquiryQuestion::getPerformance()
        ]);
    }
}
