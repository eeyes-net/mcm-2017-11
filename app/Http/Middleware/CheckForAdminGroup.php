<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckForAdminGroup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $user = Auth::guard($guard)->user();
        if ($user->group !== 'admin' || $user->group !== 'root') {
            return redirect('/home');
        }
        return $next($request);
    }
}
