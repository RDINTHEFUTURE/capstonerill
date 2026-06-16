<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private function authorizeUser(): void
    {
        abort_if(!auth()->check() || (!auth()->user()->isManager() && !auth()->user()->isSupervisor()), 403, 'Akses ditolak.');
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
        $this->authorizeUser();
        $currentUser = auth()->user();
        $users = User::query()->latest()->paginate(10);
        return view('users.index', compact('users', 'currentUser'));
    }

    public function create()
    {
        $this->authorizeUser();

        $currentUser = auth()->user();
        $availableRoles = [];

        if ($currentUser->isManager()) {
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
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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
        $this->authorizeUser();

        $currentUser = auth()->user();
        $isSelf = $user->id === $currentUser->id;
        $availableRoles = [];

        if ($isSelf) {
            $availableRoles = [];
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
        $this->authorizeUser();

        $currentUser = auth()->user();
        $isSelf = $user->id === $currentUser->id;

        if (!$isSelf && !$this->canManageUser($user)) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pengguna ini.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['nullable', 'string'],
        ]);

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
            } else {
                abort(403, 'Akses ditolak.');
            }

            $data['role'] = $validated['role'];
        }

        if (!empty($validated['password'])) {
            $canChangePassword = $isSelf || $currentUser->roleLevel() > $user->roleLevel();
            if (!$canChangePassword) {
                abort(403, 'Anda tidak memiliki akses untuk mengubah kata sandi pengguna ini.');
            }
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
