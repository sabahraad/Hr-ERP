<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('access_token') && !empty(session()->get('access_token')) && session('role') == 3) {
            return $next($request);
        }
        else{
            return redirect('/login')->with('error', 'You do not have permission to log in.');
        }
        
    }
}
