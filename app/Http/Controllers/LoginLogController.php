<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Http\Request;

class LoginLogController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = auth()->user();
        $query = LoginLog::with('user')->latest('logged_at');

        // Staff can only see their own logs
        if ($currentUser->isStaff()) {
            $query->where('user_id', $currentUser->id);
        }
        // Supervisor can see Staff logs + own
        elseif ($currentUser->isSupervisor()) {
            $query->where(function ($q) use ($currentUser) {
                $q->where('user_id', $currentUser->id)
                  ->orWhereHas('user', function ($q2) {
                      $q2->where('role', 'Accounting Staff');
                  });
            });
        }
        // Manager can see Supervisor + Staff + own
        elseif ($currentUser->isManager()) {
            $query->where(function ($q) use ($currentUser) {
                $q->where('user_id', $currentUser->id)
                  ->orWhereHas('user', function ($q2) {
                      $q2->whereIn('role', ['Accounting Supervisor', 'Accounting Staff']);
                  });
            });
        }
        // Admin can see all

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('success', $request->status === 'success');
        }

        $logs = $query->paginate(20)->withQueryString();

        $users = User::orderBy('name')->get();

        return view('activity.login-logs', compact('logs', 'users'));
    }
}
