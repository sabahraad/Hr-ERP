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
        
        if (!session('access_token') && session('role') !== 2) {
            return redirect('/login')->with('error', 'You do not have permission to log in.');
        }

        return $next($request);
        
    }
}
// !session('access_token') &&