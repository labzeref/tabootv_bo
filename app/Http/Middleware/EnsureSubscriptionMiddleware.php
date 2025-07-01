<?php

namespace App\Http\Middleware;

use App\Exceptions\NotSubscribedException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EnsureSubscriptionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->subscribed()) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            Log::info('User is not subscribed.' , ['user_id' => $request->user()->email]);
            throw new NotSubscribedException();
        }

        return to_route('payment.choose-plan')->with('status', 'You need to subscribe to access this page.');
    }
}
