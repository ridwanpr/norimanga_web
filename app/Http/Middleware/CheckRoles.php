<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRoles
{
    public function handle(Request $request, Closure $next, ...$roles): mixed
    {
        if (Auth::check() && Auth::user()->hasRole($roles)) {
            return $next($request);
        }

        return redirect()->route('home')->with('error', 'You do not have access to this page.');
    }
}
