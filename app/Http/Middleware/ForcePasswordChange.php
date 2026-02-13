<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        if (
            auth()->check() &&
            auth()->user()->must_change_password &&
            !$request->routeIs(
                'change_password.form',
                'change_password',
                'logout'
            )
        ) {
            return redirect()->route('change_password.form');
        }

        return $next($request);
    }
}

