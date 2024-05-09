<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedRoles = [2, 3];
        if(auth()->check() && in_array(auth()->user()->role, $allowedRoles)){
            return $next($request);
        }else{
            return response()->json([
                'message' => 'Only HR can access this Api'
            ],403);
        }
       
    }
}
