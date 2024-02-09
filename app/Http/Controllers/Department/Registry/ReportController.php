<?php

namespace App\Http\Controllers\Department\Registry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Enquiry;
use App\Models\EnquiryQuestion;
use App\Exports\EnquiryExport;
use App\Exports\EnquiryQuestionExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(){

        return Inertia::render('Department/Registry/Report/Dashboard',[
            'enquiryQuestionStat'=>EnquiryQuestion::getPerformance()
        ]);
    }

    public function exportEnquiries(Request $request){
        $fileName='enquiries_'.Date('Y-m-d').'.xlsx';
        return Excel::download(new EnquiryExport($request->period), $fileName);
    }

    public function exportQuestions(Request $request){
        $fileName='questions_'.Date('Y-m-d').'.xlsx';
        return Excel::download(new EnquiryQuestionExport($request->period), $fileName);
    }

}
