<?php

namespace App\Http\Controllers\Staff\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminLoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use LdapRecord\Models\ActiveDirectory\User as LdapUser; // Import LdapRecord User model
use App\Models\User as AppUser; // Import your application's User model



class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
            return Inertia::render('Staff/Auth/AdminLogin', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    // public function store(Request $request){
    //     dd($request->all());
    //     $request->session()->flush();
    //     $request->authenticate();
    //     $request->session()->regenerate();
    //     if(auth()->user()){
    //         dd($auth()->user(), $request->all());
    //     }
    // }
    
    public function store(AdminLoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('username', 'password');

        $guard = $request->local ? 'admin_local' : 'admin';
        Auth::shouldUse($guard);
        $request->session()->flush();

        if ($guard === 'admin') {
            // Attempt LDAP authentication using LdapRecord
            dd($this->attemptLdapAuthentication($guard, $credentials));
            if ($this->attemptLdapAuthentication($guard, $credentials)) {
                // LDAP authentication successful
                Log::info('LDAP authentication successful for user: ' . $request->input('username'));
                // Find or create the user in the database
                $user = $this->findOrCreateUserFromLdap($request->input('username'));
                Auth::guard($guard)->login($user); // Log in the user
            } else {
                // LDAP authentication failed
                Log::warning('LDAP authentication failed for user: ' . $request->input('username'));
                return back()->withErrors(['username' => 'LDAP Authentication failed.']);
            }
        } else {
            // Use the standard database authentication for 'admin_local'
            $request->authenticate($guard);
        }


        $request->session()->regenerate();

        if(auth()->user()){
            if(auth()->user()->hasRole('admin')){
                return redirect()->intended('staff');
            }else{
                return redirect()->intended('guest');
            }
        }else{
            return redirect()->intended(RouteServiceProvider::HOME);

        }

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
    public function logout(Request $request) {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }


    private function attemptLdapAuthentication($guard, $credentials): bool
    {
        // dd($guard, $credentials);
        try {
            // Attempt to authenticate against LDAP
            return Auth::attempt($credentials); // LdapRecord handles the LDAP connection
        } catch (\Exception $e) {
            Log::error('LDAP Authentication Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Find or create a user in your database based on LDAP attributes.
     */
    private function findOrCreateUserFromLdap(string $username): AppUser // Use your AppUser model
    {
        // First, try to find the user in your database by username
        $user = AppUser::where('username', $username)->first();

        if (!$user) {
            // User doesn't exist in the database, so fetch their LDAP information
            $ldapUser = LdapUser::where('samaccountname', '=', $username)->first();

            if ($ldapUser) {
                // Create a new user in your database
                $user = AppUser::create([
                    'username' => $username,
                    'email' => $ldapUser->mail ?? null, // Get email from LDAP
                    'name' => $ldapUser->cn ?? null,   // Get name from LDAP
                    'password' => bcrypt(Str::random(40)), // Generate a random password
                    // Add other attributes as needed, mapping LDAP attributes to your User model
                ]);
            } else {
                // Log that the user was not found in LDAP
                Log::warning('LDAP User not found: ' . $username);
                return null; // Or handle the case where the LDAP user is not found
            }
        }

        return $user;
    }

}
