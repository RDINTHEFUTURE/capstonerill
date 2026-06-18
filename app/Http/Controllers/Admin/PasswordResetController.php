<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetRequest;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    public function index()
    {
        $requests = PasswordResetRequest::with('user', 'approver')
            ->latest()
            ->paginate(20);

        return view('admin.password-resets', compact('requests'));
    }

    public function approve(PasswordResetRequest $passwordResetRequest)
    {
        $passwordResetRequest->status = 'approved';
        $passwordResetRequest->approved_by = auth()->id();
        $passwordResetRequest->approved_at = now();
        $passwordResetRequest->save();

        return back()->with('success', 'Permintaan reset password disetujui.');
    }

    public function reject(PasswordResetRequest $passwordResetRequest)
    {
        $passwordResetRequest->status = 'rejected';
        $passwordResetRequest->approved_by = auth()->id();
        $passwordResetRequest->approved_at = now();
        $passwordResetRequest->save();

        return back()->with('success', 'Permintaan reset password ditolak.');
    }
}
