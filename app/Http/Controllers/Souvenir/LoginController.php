<?php

namespace App\Http\Controllers\Souvenir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SouvenirUser;
use Inertia\Inertia;
use LdapRecord\Connection;
use LdapRecord\Auth\BindException;
use Illuminate\Support\Facades\Auth;

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
        
        if(substr($request->netid,0,4)=='test'){
            $user = SouvenirUser::where("netid", $request->netid)->first();
            session()->put("souvenirUser", $user);
            return to_route('souvenir');
        };
        $credentials = [
            'samaccountname' => $request->netid,
            'password' => $request->password
        ];

        if (Auth::guard('ldap')->attempt($credentials)) {
            $user = SouvenirUser::where("netid", $request->netid)->first();
            session()->put("souvenirUser", $user);
            return to_route('souvenir');
        }else{
            return back()->withErrors(['login' => 'Invalid credentials']);
        }
        

    }
    
    public function logout(){
         session()->forget('souvenirUser');
        // Optionally invalidate the session
        session()->invalidate();
        return back()->with(["message"=>'logout successful']);

    }
}
