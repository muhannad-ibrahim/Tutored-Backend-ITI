<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Facades\JWTAuth;


class checkStudent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::guard('students')->check()) {
            try {
                JWTAuth::parseToken()->authenticate();
            } catch (Exception $exception) {
                if ($exception instanceof TokenInvalidException) {
                    return response()->json('Invalid Token');
                } else if ($exception instanceof TokenExpiredException) {
                    return response()->json('Expired Token');
                } else {
                    return response()->json('Token not found please login');
                }
            }
            return $next($request);
        }
        return response()->json('Please login first');
    }
}
