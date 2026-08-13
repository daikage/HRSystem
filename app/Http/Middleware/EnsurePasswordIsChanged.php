<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordIsChanged
{
    /**
     * Redirect users who were issued a temporary password (and have been
     * flagged to change it) to the forced password change screen until they
     * have set a new password.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->must_change_password && ! $request->routeIs('forced-password.*')) {
            return redirect()->route('forced-password.edit');
        }

        return $next($request);
    }
}
