@extends('layouts.mazer')

@section('title', 'Profil Pengguna - ' . $profileUser->name)

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <h3>Profil Pengguna</h3>
        <a class="btn btn-secondary" href="{{ route('users.index') }}">Kembali</a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($profileUser->avatar)
                        <img src="{{ $profileUser->avatar }}" alt="Avatar" class="rounded-circle mx-auto mb-3" style="width:80px;height:80px;object-fit:cover;">
                    @else
                        <div class="avatar avatar-lg rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width:80px;height:80px;font-size:32px;font-weight:600;">
                            {{ strtoupper(substr($profileUser->name, 0, 1)) }}
                        </div>
                    @endif
                    <h5 class="mb-1">{{ $profileUser->name }}</h5>
                    @if($profileUser->isAdmin())
                        <span class="badge bg-dark">{{ $profileUser->role }}</span>
                    @elseif($profileUser->isManager())
                        <span class="badge bg-danger">{{ $profileUser->role }}</span>
                    @elseif($profileUser->isSupervisor())
                        <span class="badge bg-warning text-dark">{{ $profileUser->role }}</span>
                    @else
                        <span class="badge bg-success">{{ $profileUser->role }}</span>
                    @endif
                    <p class="text-muted mt-2 mb-0">{{ $profileUser->email }}</p>
                    <small class="text-muted">Bergabung sejak {{ $profileUser->created_at->format('d M Y') }}</small>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Akun</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted" style="width:200px;">Nama Lengkap</td>
                            <td>{{ $profileUser->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email</td>
                            <td>{{ $profileUser->email }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Role</td>
                            <td>
                                @if($profileUser->isAdmin())
                                    <span class="badge bg-dark">{{ $profileUser->role }}</span>
                                @elseif($profileUser->isManager())
                                    <span class="badge bg-danger">{{ $profileUser->role }}</span>
                                @elseif($profileUser->isSupervisor())
                                    <span class="badge bg-warning text-dark">{{ $profileUser->role }}</span>
                                @else
                                    <span class="badge bg-success">{{ $profileUser->role }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Terdaftar Sejak</td>
                            <td>{{ $profileUser->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Terakhir Diperbarui</td>
                            <td>{{ $profileUser->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                        @if($profileUser->creator)
                            <tr>
                                <td class="text-muted">Dibuat Oleh</td>
                                <td>{{ $profileUser->creator->name }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
