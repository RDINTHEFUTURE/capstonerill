@extends('layouts.mazer')

@section('title', 'Chart of Accounts')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <h3>Chart of Accounts</h3>
        <a class="btn btn-primary" href="{{ route('chart-of-accounts.create') }}">+ Akun Baru</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

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
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('chart-of-accounts.edit', $account) }}">Edit</a>
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
