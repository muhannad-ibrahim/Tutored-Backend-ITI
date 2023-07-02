<?php

namespace App\Http\Middleware;

use App\Models\Student;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $user = Student::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Email does not exist.'], 404);
        }
    

        if ($user && !$user->hasVerifiedEmail()) {
            return redirect()->route('verification.send')->with('message', 'Please verify your email.')->with('_method', 'POST');
        }

        return $next($request);
    }
}
