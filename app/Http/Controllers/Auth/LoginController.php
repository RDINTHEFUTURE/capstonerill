<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            LoginLog::create([
                'user_id' => auth()->id(),
                'email' => auth()->user()->email,
                'success' => true,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'logged_at' => now(),
            ]);

            return redirect()->intended(route('home'));
        }

        LoginLog::create([
            'email' => $credentials['email'],
            'success' => false,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'logged_at' => now(),
        ]);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
