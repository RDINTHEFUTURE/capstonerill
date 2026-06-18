@extends('layouts.mazer')

@section('title', 'Reset Password Requests')

@section('content')
    <div class="page-heading">
        <h3>Permintaan Reset Password</h3>
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
                            <th>Pengguna</th>
                            <th>Email</th>
                            <th>IP Address</th>
                            <th>Perangkat</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                            <tr>
                                <td>{{ $req->user?->name ?? '-' }}</td>
                                <td>{{ $req->user?->email ?? '-' }}</td>
                                <td>{{ $req->ip_address ?? '-' }}</td>
                                <td><small>{{ \Illuminate\Support\Str::limit($req->user_agent, 40) ?? '-' }}</small></td>
                                <td>
                                    @if($req->isPending())
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @elseif($req->isApproved())
                                        <span class="badge bg-success">Disetujui</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>{{ $req->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    @if($req->isPending())
                                        <form method="POST" action="{{ route('admin.password-resets.approve', $req) }}" class="d-inline" onsubmit="return confirm('Setujui permintaan reset password ini?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.password-resets.reject', $req) }}" class="d-inline" onsubmit="return confirm('Tolak permintaan reset password ini?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                                        </form>
                                    @elseif($req->isApproved())
                                        <span class="text-muted">
                                            Link: <code>{{ route('password-recovery.show', $req->token) }}</code>
                                        </span>
                                    @else
                                        <span class="text-muted">Ditolak oleh {{ $req->approver?->name ?? '-' }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">Belum ada permintaan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $requests->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
