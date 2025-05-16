<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use LdapRecord\Container;
use App\Models\AdminUser;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

use Inertia\Inertia;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return Inertia::render('Staff/Auth/AdminLogin', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);

    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'local' => 'required|boolean'
            //'login_type' => 'required|in:local,ldap',
        ]);
        $credentials = $request->only('username', 'password');
        $isLocalUser = $request->input('local');
        

        if ($isLocalUser) {
            Auth::shouldUse('admin');
            $adminUser=AdminUser::where('username',$request->username)->first();
            
            Auth::guard('admin')->login($adminUser, $request->remember);
            // if (Auth::guard('admin')->attempt($credentials, $request->remember)) {
            //dd($credentials, $isLocalUser, $adminUser, auth()->user());
            return redirect()->intended('/staff');
            if($adminUser){
                return redirect()->intended('/staff');
            }

        } else {
            // LDAP Authentication
            try {
                $credentials = [
                    'samaccountname' => $request->username,
                    'password' => $request->password
                ];
   
                // $connection = Container::getConnection('default');
                //dd($connection, $credentials, Auth::guard('ldap')->attempt($credentials));
                
                if (Auth::guard('ldap')->attempt($credentials)) {
                    // If you want to synchronize LDAP user with local database
                    $ldapUser = Auth::guard('ldap')->user();
                    $adminUser = AdminUser::firstOrCreate(
                        ['email' => $ldapUser->getFirstAttribute('mail')],
                        [
                            'name' => $ldapUser->getFirstAttribute('cn'),
                            'username' => $ldapUser->getFirstAttribute('samaccountname'),
                            'password' => bcrypt($request->password), // Not used for LDAP auth
                        ]
                    );
                    Auth::shouldUse('admin');
                    Auth::guard('admin')->login($adminUser, $request->remember);
                    return redirect()->intended('/staff');
                }
            } catch (\Exception $e) {
                dd('ldap error', $e->getMessage());

                \Log::error('LDAP Login Error: '.$e->getMessage());
            }
        }

        return redirect()->back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'These credentials do not match our records.']);
    }

    public function logout()
    {
        //dd('admin logout');
        Auth::guard('admin')->logout();
        Auth::guard('ldap')->logout();
        return redirect('/');
    }
}