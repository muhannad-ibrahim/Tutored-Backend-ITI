<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class VerifyEmailController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $user = Student::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return redirect('http://localhost:4200/main/login/student');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect('http://localhost:4200/main/login/student');
    }
}
