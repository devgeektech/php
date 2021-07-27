<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class RouteAuthenticate extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $token = Cookie::get('j0');
            $request->headers->set('Authorization', 'Bearer '.$token);
            JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return redirect()->guest('/login');
        }

        return $next($request);
    }
}
