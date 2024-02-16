<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\RecVacancy;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RecruitmentController extends Controller
{
    public function notifications(){
        return Inertia::render('Member/Recruitment/Notifications',[
            'vacancies'=>RecVacancy::all()
        ]);
    }
}
