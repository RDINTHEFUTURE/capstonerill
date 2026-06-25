<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.'])->withInput();
        }

        $resetRequest = PasswordResetRequest::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'token' => Str::random(64),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'reason' => $request->reason,
        ]);

        return redirect()->route('password-recovery.show', $resetRequest->token);
    }
}
