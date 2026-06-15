@extends('layouts.mazer')

@section('title', 'Edit Akun')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <h3>Edit Akun</h3>
        <a class="btn btn-secondary" href="{{ route('chart-of-accounts.show', $chartOfAccount) }}">Batal</a>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <form method="POST" action="{{ route('chart-of-accounts.update', $chartOfAccount) }}">
                @csrf
                @method('PUT')
                <x-chart-of-accounts.form :chartOfAccount="$chartOfAccount" />
                <div class="mt-3">
                    <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
