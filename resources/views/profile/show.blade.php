@extends('layouts.mazer')

@section('title', 'Profil Saya')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <h3>Profil Saya</h3>
        <a class="btn btn-primary" href="{{ route('profile.edit') }}"><i class="bi bi-pencil me-1"></i>Edit Profil</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}" alt="Avatar" class="rounded-circle mx-auto mb-3" style="width:80px;height:80px;object-fit:cover;">
                    @else
                        <div class="avatar avatar-lg rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width:80px;height:80px;font-size:32px;font-weight:600;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <span class="badge bg-primary">{{ $user->role }}</span>
                    <p class="text-muted mt-2 mb-0">{{ $user->email }}</p>
                    <small class="text-muted">Bergabung sejak {{ $user->created_at->format('d M Y') }}</small>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Foto Profil</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data" id="avatar-form">
                        @csrf
                        <div class="mb-3">
                            <input type="file" class="form-control" name="avatar" id="avatar-input" accept="image/png,image/jpeg,image/jpg" required>
                            <small class="text-muted">Format: PNG/JPG, Maks. 2MB</small>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Unggah Foto</button>
                    </form>
                    @if($user->avatar)
                        <form method="POST" action="{{ route('profile.avatar.remove') }}" class="mt-2" onsubmit="return confirm('Hapus foto profil?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">Hapus Foto</button>
                        </form>
                    @endif
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
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email</td>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Role</td>
                            <td><span class="badge bg-primary">{{ $user->role }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Terdaftar Sejak</td>
                            <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Terakhir Diperbarui</td>
                            <td>{{ $user->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Ubah Password</h5>
                </div>
                <div class="card-body">
                    @if ($errors->has('current_password'))
                        <div class="alert alert-danger">{{ $errors->first('current_password') }}</div>
                    @endif
                    @if ($errors->has('password'))
                        <div class="alert alert-danger">{{ $errors->first('password') }}</div>
                    @endif

                    <form method="POST" action="{{ route('profile.password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <input type="password" id="current_password" class="form-control" name="current_password" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password Baru</label>
                                    <input type="password" id="password" class="form-control" name="password" placeholder="Min. 4 karakter" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                    <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning">Ubah Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
