<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordRecoveryController extends Controller
{
    public function showForm(string $token)
    {
        $request = PasswordResetRequest::where('token', $token)->first();

        if (!$request) {
            abort(404, 'Token tidak valid.');
        }

        return view('auth.password-recovery', ['token' => $token, 'request' => $request]);
    }

    public function updatePassword(Request $request, string $token)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:4', 'confirmed'],
        ]);

        $resetRequest = PasswordResetRequest::where('token', $token)
            ->where('status', 'approved')
            ->first();

        if (!$resetRequest) {
            abort(404, 'Token tidak valid atau sudah digunakan.');
        }

        $user = $resetRequest->user;
        $user->update(['password' => bcrypt($request->password)]);

        $resetRequest->delete();

        return redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan login dengan password baru.');
    }
}
