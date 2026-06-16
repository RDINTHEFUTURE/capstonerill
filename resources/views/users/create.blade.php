@extends('layouts.mazer')

@section('title', 'Buat Pengguna Baru')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <h3>Buat Pengguna Baru</h3>
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
            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" id="name" class="form-control" name="name" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" class="form-control" name="email" placeholder="Masukkan alamat email" value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select id="role" class="form-select" name="role" required>
                                <option value="" disabled selected>Pilih Role</option>
                                @foreach ($availableRoles as $role)
                                    <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Kata Sandi</label>
                            <input type="password" id="password" class="form-control" name="password" placeholder="Min. 8 karakter" required>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                            <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="Ulangi kata sandi" required>
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-primary me-1">Simpan</button>
                        <button type="reset" class="btn btn-light-secondary">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
