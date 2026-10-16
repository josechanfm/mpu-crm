<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WidgetController extends Controller
{
    public function genQrcode(){
        return Inertia::render('Widget/GenQrcode',[

        ]);
    }
}
