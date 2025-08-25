<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth; // Import the Auth facade
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;


class SetLocale
{
    public function handle($request, Closure $next)
    {
        // Determine the locale based on the authenticated user (if any)
        if (Auth::guard('admin')->check()) {
            // User is authenticated with the admin guard
            $user = Auth::guard('admin')->user();
            if ($user && $user->locale) {
                $locale = $user->locale; // Get locale from user object
            } else {
                $locale = Session::get('applocale', config('app.locale')); // Fallback
            }

        } else {
            // No user authenticated (or authenticated with a different guard)
            $locale = Session::get('applocale', config('app.locale')); // Get locale from session or config
        }
        
        App::setLocale($locale);
        //dd($locale, $request, $next);
        return $next($request);
    }
}