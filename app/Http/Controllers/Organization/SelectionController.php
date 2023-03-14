<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\AdminUser;
use Inertia\Inertia;

class SelectionController extends Controller
{
    public function index(){
        $organizations=AdminUser::find(Auth()->user()->id)->organizations;
        return Inertia::render('Organization/Selection',[
            'organizations' => $organizations
        ]);
    }
}
