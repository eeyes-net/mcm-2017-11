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
        /** @var \App\User $user */
        $user = Auth::guard($guard)->user();
        if (!$user->isAdmin()) {
            return redirect('/login/admin');
        }
        session_start();
        $_SESSION['is_admin'] = true;
        return $next($request);
    }
}
