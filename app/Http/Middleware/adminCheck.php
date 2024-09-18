<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class adminCheck
{
    //role = 1 = user 
    //role = 2 = HR
    //role = 3 = AAMAR dIGITAL EXCESS
    //role = 4 = Admin
    //role = 5 = FINENCE
    //role = 6 = DIRECTOR
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('access_token') && !empty(session()->get('access_token')) && (session('role') == 4 || session('role') == 5) ) {
            return $next($request);
        }
        else{
            return redirect('/login')->with('error', 'You do not have permission to log in.');
        }
        
    }
}
