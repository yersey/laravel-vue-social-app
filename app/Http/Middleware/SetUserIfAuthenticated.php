<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate;
use Symfony\Component\HttpFoundation\Response;

class SetUserIfAuthenticated
{
    /**
     * Assigns the authenticated user to the application context 
     * if the user is logged in. 
     * Allows access to user data even on routes without required authentication.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return app(Authenticate::class)->handle($request, $next, 'sanctum');
        } catch (\Exception) {
            return $next($request);
        }
    }
}
