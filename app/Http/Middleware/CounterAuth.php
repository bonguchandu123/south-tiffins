<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CounterAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('counter_access')) {
            return redirect()->route('counter.login');
        }

        return $next($request);
    }
}