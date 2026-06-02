@extends('layouts.mazer')

@section('title', 'Detail Akun')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <h3>Detail Akun</h3>
        <div>
            <a class="btn btn-secondary" href="{{ route('chart-of-accounts.index') }}">Kembali</a>
            <a class="btn btn-outline-secondary" href="{{ route('chart-of-accounts.edit', $chartOfAccount) }}">Edit</a>
            <form method="POST" action="{{ route('chart-of-accounts.destroy', $chartOfAccount) }}" class="d-inline" onsubmit="return confirm('Hapus akun ini?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" type="submit">Hapus</button>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="details-row"><span class="key">Kode</span><div class="value">{{ $chartOfAccount->account_no_new }}</div></div>
            <div class="details-row"><span class="key">Kode Lama 1</span><div class="value">{{ $chartOfAccount->account_no_old_1 ?: '-' }}</div></div>
            <div class="details-row"><span class="key">Kode Lama 2</span><div class="value">{{ $chartOfAccount->account_no_old_2 ?: '-' }}</div></div>
            <div class="details-row"><span class="key">Nama</span><div class="value">{{ $chartOfAccount->account_name }}</div></div>
            <div class="details-row"><span class="key">Header</span><div class="value">{{ $chartOfAccount->is_header ?: '-' }}</div></div>
            <div class="details-row"><span class="key">Tipe</span><div class="value">{{ $chartOfAccount->account_type ?: '-' }}</div></div>
        </div>
    </div>
@endsection
