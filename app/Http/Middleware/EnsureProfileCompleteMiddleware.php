<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureProfileCompleteMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->profile_completed) {
            return $next($request);
        }

        return to_route('profile.complete.create')->with('error', 'Please complete your profile to continue');
    }
}
