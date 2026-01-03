<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * If the user is already authenticated, redirect to a home/dashboard route.
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect()->route('dashboard');
            }
        }

        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
