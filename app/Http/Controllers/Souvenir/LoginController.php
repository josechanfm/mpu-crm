<?php

namespace App\Http\Controllers\Souvenir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SouvenirUser;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function login(Request $request){
        return Inertia::render("Souvenir/Login");
    }

    public function checker(Request $request){ 
        $this->validate($request, [
            "netid"=> "required",
            "password"=> "required",
        ]);
        $user = SouvenirUser::where("netid", $request->netid)->first();
        session()->put("souvenirUser", $user);
        return to_route('souvenir');

    }
    
    public function logout(){
         session()->forget('souvenirUser');
        // Optionally invalidate the session
        session()->invalidate();
        return back()->with(["message"=>'logout successful']);

    }
}
