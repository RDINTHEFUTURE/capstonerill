@extends('layouts.mazer')

@section('title', 'Buat Akun')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <h3>Buat Akun</h3>
        <a class="btn btn-secondary" href="{{ route('chart-of-accounts.index') }}">Kembali</a>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <form method="POST" action="{{ route('chart-of-accounts.store') }}">
                @csrf
                <x-chart-of-accounts.form />
                <div class="mt-3">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
