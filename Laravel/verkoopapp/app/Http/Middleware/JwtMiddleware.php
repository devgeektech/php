<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
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
        /*try {
            $user = JWTAuth::parseToken()->authenticate();
            if (isset($user) && $user->is_active == 0 && $user->updated_by == 1) {
                return response()->json(['invalid_token'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['invalid_token'], 401);
        }*/

        return $next($request);
    }
}
