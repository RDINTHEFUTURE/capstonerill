@extends('layouts.mazer')

@section('title', $isSelf ? 'Edit Profil' : 'Edit Pengguna')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <h3>{{ $isSelf ? 'Edit Profil' : 'Edit Pengguna' }}</h3>
        <a class="btn btn-secondary" href="{{ route('users.index') }}">Kembali</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" id="name" class="form-control" name="name" placeholder="Masukkan nama lengkap" value="{{ old('name', $user->name) }}" required>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" class="form-control" name="email" placeholder="Masukkan alamat email" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>

                    @if(!$isSelf && count($availableRoles) > 0)
                    <div class="col-md-6 col-12">
                        <div class="form-group mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select id="role" class="form-select" name="role" required>
                                <option value="" disabled>Pilih Role</option>
                                @foreach ($availableRoles as $role)
                                    <option value="{{ $role }}" {{ old('role', $user->role) === $role ? 'selected' : '' }}>{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif

                    @if($isSelf)
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini <small class="text-muted">(Wajib diisi untuk mengubah data apapun)</small></label>
                            <input type="password" id="current_password" class="form-control" name="current_password" placeholder="Masukkan password saat ini" required>
                        </div>
                    </div>
                    @endif

                    @if($canChangePassword)
                    <div class="col-md-6 col-12">
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Kata Sandi Baru <small class="text-muted">(Kosongkan jika tidak ingin mengubah)</small></label>
                            <input type="password" id="password" class="form-control" name="password" placeholder="Min. 4 karakter">
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="Ulangi kata sandi">
                        </div>
                    </div>
                    @endif

                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-primary me-1">Simpan Perubahan</button>
                        <a href="{{ route('users.index') }}" class="btn btn-light-secondary">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
