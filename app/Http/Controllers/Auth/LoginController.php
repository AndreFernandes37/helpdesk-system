<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function authenticated(Request $request, $user)
    {
        if (! $user->active) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'A tua conta está desativada.',
            ]);
        }
    }
}
