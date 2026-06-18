@extends('layouts.mazer')

@section('title', 'Login Logs')

@section('content')
    <div class="page-heading">
        <h3>Riwayat Login</h3>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('activity.login-logs') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Pengguna</label>
                    <select name="user_id" class="form-select">
                        <option value="">Semua</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Berhasil</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Gagal</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                @if(request('user_id') || request('status'))
                    <div class="col-md-2">
                        <a href="{{ route('activity.login-logs') }}" class="btn btn-secondary w-100">Reset</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>IP Address</th>
                            <th>Perangkat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->logged_at ? $log->logged_at->format('d M Y H:i:s') : '-' }}</td>
                                <td>{{ $log->email }}</td>
                                <td>
                                    @if($log->success)
                                        <span class="badge bg-success">Berhasil</span>
                                    @else
                                        <span class="badge bg-danger">Gagal</span>
                                    @endif
                                </td>
                                <td>{{ $log->ip_address ?? '-' }}</td>
                                <td><small>{{ \Illuminate\Support\Str::limit($log->user_agent, 50) }}</small></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">Belum ada riwayat login.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $logs->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
