<?php

namespace App\Http\Middleware;

use App\Models\Student;
use Closure;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class VerifyEmailBeforeLogin
{
    public function handle($request, Closure $next)
    {
        $credentials = $request->only('email', 'password');
    
        $user = Student::where('email', $credentials['email'])->first();
    
        // Check if the user exists and their email is verified
        if ($user && $user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Your email address is not verified.'], 403)
                : redirect()->route('verification.notice');
        }
    
        return $next($request);
    }
    
}
