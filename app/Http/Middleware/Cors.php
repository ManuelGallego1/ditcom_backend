<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cors
{
    protected $allowedOrigins = [
        'http://localhost:3000',
        'https://ditcomfrontend.amcdevcode.com',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $origin = $request->headers->get('Origin');

        $allowedOrigin = in_array($origin, $this->allowedOrigins) ? $origin : '';

        if ($request->getMethod() === "OPTIONS") {
            return response('', 204)
                ->header('Access-Control-Allow-Origin', $allowedOrigin)
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization')
                ->header('Access-Control-Allow-Credentials', 'true');
        }

        $response = $next($request);

        if ($allowedOrigin) {
            $response->headers->set('Access-Control-Allow-Origin', $allowedOrigin);
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        }

        return $response;
    }
}
