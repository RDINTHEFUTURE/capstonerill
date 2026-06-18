@extends('layouts.mazer')

@section('title', 'Daftar Pengguna')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <h3>Daftar Pengguna</h3>
        @if($currentUser->isAdmin() || $currentUser->isManager() || $currentUser->isSupervisor())
            <a class="btn btn-primary" href="{{ route('users.create') }}">+ Buat Pengguna Baru</a>
        @endif
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Akun yang Sedang Digunakan</h5>
        </div>
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-lg bg-primary text-white d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; border-radius: 50%; font-size: 1.5rem; font-weight: bold;">
                        {{ strtoupper(substr($currentUser->name, 0, 1)) }}
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $currentUser->name }}</h5>
                        <span class="text-muted">{{ $currentUser->email }}</span>
                        <div class="mt-1">
                            @if($currentUser->isAdmin())
                                <span class="badge bg-dark">{{ $currentUser->role }}</span>
                            @elseif($currentUser->isManager())
                                <span class="badge bg-danger">{{ $currentUser->role }}</span>
                            @elseif($currentUser->isSupervisor())
                                <span class="badge bg-warning text-dark">{{ $currentUser->role }}</span>
                            @else
                                <span class="badge bg-success">{{ $currentUser->role }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <a href="{{ route('users.edit', $currentUser) }}" class="btn btn-outline-primary">Edit Profil</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Terdaftar Pada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>
                                    @if($user->id === $currentUser->id || $currentUser->roleLevel() > $user->roleLevel())
                                        {{ $user->email }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->isAdmin())
                                        <span class="badge bg-dark">{{ $user->role }}</span>
                                    @elseif($user->isManager())
                                        <span class="badge bg-danger">{{ $user->role }}</span>
                                    @elseif($user->isSupervisor())
                                        <span class="badge bg-warning text-dark">{{ $user->role }}</span>
                                    @else
                                        <span class="badge bg-success">{{ $user->role }}</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at ? $user->created_at->format('d M Y H:i') : '-' }}</td>
                                <td>
                                    @if($user->id === $currentUser->id)
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary">Edit Profil</a>
                                    @elseif($currentUser->roleLevel() > $user->roleLevel())
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" class="d-inline" onsubmit="return confirm('Hapus pengguna ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">Belum ada data pengguna.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $users->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
