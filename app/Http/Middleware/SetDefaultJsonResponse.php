<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SetDefaultJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $statusCode = $response->getStatusCode();

        if ($response instanceof JsonResponse) {
            $responseData = $response->getData(true);

            // Set default values for missing response fields
            $responseData['message'] = $responseData['message'] ?? null;
            $responseData['status'] = $statusCode ?? '';
            $responseData['data'] = $responseData['data'] ?? '';
            // $responseData['error'] = $responseData['error'] ?? '';

            $response->setData($responseData);
        }

        return $response;
    }
}
