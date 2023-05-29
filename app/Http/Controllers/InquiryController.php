<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class InquiryController extends Controller
{
    
    public function index(){
        return Inertia::render('Inquiry/Form',[

        ]);
    }
}
