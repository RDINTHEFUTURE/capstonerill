@extends('layouts.mazer')

@section('title', 'Edit Profil')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <h3>Edit Profil</h3>
        <a class="btn btn-secondary" href="{{ route('profile.show') }}">Kembali</a>
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
        <div class="card-header">
            <h5 class="card-title mb-0">Informasi Profil</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="current_password" class="form-label">Password Saat Ini <small class="text-muted">(Wajib diisi untuk mengubah data)</small></label>
                    <input type="password" id="current_password" class="form-control" name="current_password" placeholder="Masukkan password saat ini" required>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary me-1">Simpan Perubahan</button>
                    <a href="{{ route('profile.show') }}" class="btn btn-light-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
