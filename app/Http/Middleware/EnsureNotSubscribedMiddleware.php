<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureNotSubscribedMiddleware
{
    public function handle(Request $request, Closure $next)
    {

        if ($request->user()->subscribed()) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
