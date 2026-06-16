@extends('layouts.mazer')

@section('title', 'Daftar Pengguna')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <h3>Daftar Pengguna</h3>
        <a class="btn btn-primary" href="{{ route('users.create') }}">+ Buat Pengguna Baru</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

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
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->isManager())
                                        <span class="badge bg-danger">{{ $user->role }}</span>
                                    @elseif($user->isSupervisor())
                                        <span class="badge bg-warning text-dark">{{ $user->role }}</span>
                                    @else
                                        <span class="badge bg-success">{{ $user->role }}</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at ? $user->created_at->format('d M Y H:i') : '-' }}</td>
                                <td>
                                    @if(auth()->user()->isManager() || (auth()->user()->isSupervisor() && $user->isStaff()))
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
