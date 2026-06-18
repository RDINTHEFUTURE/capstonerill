<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private function authorizeUser(): void
    {
        abort_if(!auth()->check() || (!auth()->user()->isAdmin() && !auth()->user()->isManager() && !auth()->user()->isSupervisor()), 403, 'Akses ditolak.');
    }

    private function canManageUser(User $target): bool
    {
        $currentUser = auth()->user();
        if ($target->id === $currentUser->id) {
            return false;
        }
        return $currentUser->roleLevel() > $target->roleLevel();
    }

    public function index()
    {
        $currentUser = auth()->user();
        $users = User::query()->latest()->paginate(10);
        return view('users.index', compact('users', 'currentUser'));
    }

    public function create()
    {
        $this->authorizeUser();

        $currentUser = auth()->user();
        $availableRoles = [];

        if ($currentUser->isAdmin()) {
            $availableRoles = [User::ROLE_ADMIN, User::ROLE_MANAGER, User::ROLE_SUPERVISOR, User::ROLE_STAFF];
        } elseif ($currentUser->isManager()) {
            $availableRoles = [User::ROLE_SUPERVISOR, User::ROLE_STAFF];
        } elseif ($currentUser->isSupervisor()) {
            $availableRoles = [User::ROLE_STAFF];
        }

        return view('users.create', compact('availableRoles'));
    }

    public function store(Request $request)
    {
        $this->authorizeUser();

        $currentUser = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
            'role' => ['required', 'string'],
        ]);

        if ($currentUser->isSupervisor()) {
            if ($validated['role'] !== User::ROLE_STAFF) {
                abort(403, 'Supervisor hanya dapat membuat akun Staff.');
            }
        } elseif ($currentUser->isManager()) {
            if (!in_array($validated['role'], [User::ROLE_SUPERVISOR, User::ROLE_STAFF])) {
                abort(403, 'Manager hanya dapat membuat akun Supervisor atau Staff.');
            }
        } elseif ($currentUser->isAdmin()) {
            if (!in_array($validated['role'], [User::ROLE_ADMIN, User::ROLE_MANAGER, User::ROLE_SUPERVISOR, User::ROLE_STAFF])) {
                abort(403, 'Role tidak valid.');
            }
        } else {
            abort(403, 'Akses ditolak.');
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('users.index')->with('success', 'Akun berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $currentUser = auth()->user();
        $isSelf = $user->id === $currentUser->id;

        if (!$isSelf) {
            $this->authorizeUser();
        }

        $availableRoles = [];

        if ($isSelf) {
            $availableRoles = [];
        } elseif ($currentUser->isAdmin()) {
            $availableRoles = [User::ROLE_ADMIN, User::ROLE_MANAGER, User::ROLE_SUPERVISOR, User::ROLE_STAFF];
        } elseif ($currentUser->isManager()) {
            $availableRoles = [User::ROLE_SUPERVISOR, User::ROLE_STAFF];
        } elseif ($currentUser->isSupervisor()) {
            $availableRoles = [User::ROLE_STAFF];
        }

        if (!$isSelf && !$this->canManageUser($user)) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pengguna ini.');
        }

        $canChangePassword = $isSelf || $currentUser->roleLevel() > $user->roleLevel();

        return view('users.edit', compact('user', 'availableRoles', 'isSelf', 'canChangePassword'));
    }

    public function update(Request $request, User $user)
    {
        $currentUser = auth()->user();
        $isSelf = $user->id === $currentUser->id;

        if (!$isSelf) {
            $this->authorizeUser();
        }

        if (!$isSelf && !$this->canManageUser($user)) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pengguna ini.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'current_password' => $isSelf ? ['required', 'string'] : ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:4', 'confirmed'],
            'role' => ['nullable', 'string'],
        ]);

        // Self-edit: always verify current password
        if ($isSelf) {
            if (!\Illuminate\Support\Facades\Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini salah.'])->withInput();
            }
        }

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!$isSelf) {
            if (empty($validated['role'])) {
                abort(400, 'Role wajib diisi.');
            }

            if ($currentUser->isSupervisor()) {
                if ($validated['role'] !== User::ROLE_STAFF) {
                    abort(403, 'Supervisor hanya dapat menetapkan akun Staff.');
                }
            } elseif ($currentUser->isManager()) {
                if (!in_array($validated['role'], [User::ROLE_SUPERVISOR, User::ROLE_STAFF])) {
                    abort(403, 'Manager hanya dapat menetapkan akun Supervisor atau Staff.');
                }
            } elseif ($currentUser->isAdmin()) {
                if (!in_array($validated['role'], [User::ROLE_ADMIN, User::ROLE_MANAGER, User::ROLE_SUPERVISOR, User::ROLE_STAFF])) {
                    abort(403, 'Role tidak valid.');
                }
            } else {
                abort(403, 'Akses ditolak.');
            }

            $data['role'] = $validated['role'];
        }

        if (!empty($validated['password'])) {
            $data['password'] = bcrypt($validated['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->authorizeUser();

        $currentUser = auth()->user();

        if ($user->id === $currentUser->id) {
            abort(403, 'Tidak dapat menghapus akun sendiri.');
        }

        if (!$this->canManageUser($user)) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus pengguna ini.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Akun berhasil dihapus.');
    }
}
