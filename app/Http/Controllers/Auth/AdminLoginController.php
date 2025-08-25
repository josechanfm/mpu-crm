<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use LdapRecord\Container;
use App\Models\AdminUser;
use App\Models\Department;
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
            $adminUser=AdminUser::where('username',$request->username)->first();
            $credentials = [
                'username' => $request->username,
                'password' => $request->password
            ];
            if(Auth::guard('admin')->attempt($credentials)){
                $adminUser = Auth::guard('admin')->user();
                // Auth::shouldUse('admin');
                // Auth::guard('admin')->login($adminUser, $request->remember);
                //$request->session()->regenerate();
                return redirect('/master');
            }else{
                return redirect()->back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'These credentials do not match our records.']);
            };
        } else {
            // LDAP Authentication
            try {
                $credentials = [
                    'samaccountname' => $request->username,
                    'password' => $request->password
                ];
   
                //$connection = Container::getConnection('default');
                //dd($connection, $credentials, Auth::guard('ldap')->attempt($credentials));
                dd($credentials, Auth::guard('ldap')->attempt($credentials));
                if (Auth::guard('ldap')->attempt($credentials)) {
                    //dd('ldap', Auth::guard('ldap')->user());
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