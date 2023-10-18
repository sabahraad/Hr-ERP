<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session('access_token') && session('role') == 2 ) {
            return $next($request);
        }

        return redirect('/login-form')->with('error', 'You do not have permission to log in.');
        
    }
}
// !session('access_token') &&