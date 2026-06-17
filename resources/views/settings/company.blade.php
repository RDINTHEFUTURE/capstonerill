@extends('layouts.mazer')

@section('title', 'Profil Perusahaan')

@section('content')
    <div class="page-heading">
        <h3>Profil Perusahaan</h3>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('settings.company.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="form-group mb-3">
                            <label class="form-label">Nama Perusahaan</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $profile->name) }}">
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group mb-3">
                            <label class="form-label">NPWP</label>
                            <input type="text" name="npwp" class="form-control" value="{{ old('npwp', $profile->npwp) }}" maxlength="32">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" class="form-control" rows="3">{{ old('address', $profile->address) }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $profile->phone) }}">
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $profile->email) }}">
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group mb-3">
                            <label class="form-label">Logo Perusahaan</label>
                            <input type="file" name="logo" class="form-control" accept="image/png,image/jpeg">
                            @if($profile->logo)
                                <div class="mt-2">
                                    <img src="{{ $profile->logo }}" alt="Logo" style="max-height: 80px;">
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
