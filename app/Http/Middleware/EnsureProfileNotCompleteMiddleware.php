<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureProfileNotCompleteMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()->profile_completed) {
            return $next($request);
        }

        return to_route('home');
    }
}
