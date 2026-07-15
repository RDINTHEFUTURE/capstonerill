@extends('layouts.mazer')

@section('title', 'Chart of Accounts')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <h3>Chart of Accounts</h3>
        @if($isManager || auth()->user()->isAdmin())
            <a class="btn btn-primary" href="{{ route('chart-of-accounts.create') }}">+ Akun Baru</a>
        @endif
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('chart-of-accounts.index') }}" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label">Cari Akun</label>
                    <input type="text" name="search" class="form-control" placeholder="Nomor atau nama akun..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Cari</button>
                </div>
                @if(request('search'))
                    <div class="col-md-2">
                        <a href="{{ route('chart-of-accounts.index') }}" class="btn btn-secondary w-100">Reset</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nomor Akun</th>
                            <th>Nama Akun</th>
                            <th>Header</th>
                            <th>Tipe</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accounts as $account)
                            <tr>
                                <td>{{ $account->account_no_new }}</td>
                                <td>{{ $account->account_name }}</td>
                                <td>{{ $account->is_header ?: '-' }}</td>
                                <td>{{ $account->account_type ?: '-' }}</td>
                                <td>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('chart-of-accounts.show', $account) }}">Detail</a>
                                    <a class="btn btn-sm btn-outline-success" href="{{ route('ledger.show', $account->account_no_new) }}">Saldo</a>
                                    @if($isManager)
                                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('chart-of-accounts.edit', $account) }}">Edit</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $accounts->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
