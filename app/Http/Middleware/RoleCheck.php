<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleCheck
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
        $allowedRoles = [2, 3, 6];
        if(auth()->check() && in_array(auth()->user()->role, $allowedRoles)){
            return $next($request);
        }else{
            return response()->json([
                'message' => 'Only HR can access this Api'
            ],403);
        }
       
    }
}
