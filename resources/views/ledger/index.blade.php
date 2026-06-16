@extends('layouts.mazer')

@section('title', 'General Ledger')

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <h3>General Ledger</h3>
        <a class="btn btn-success" href="{{ route('reports.export-trial-balance') }}">Export CSV</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Trial Balance</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Kode Akun</th>
                            <th>Nama Akun</th>
                            <th>Tipe</th>
                            <th class="text-end">Debit</th>
                            <th class="text-end">Kredit</th>
                            <th class="text-end">Saldo</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accountData as $item)
                            <tr>
                                <td>{{ $item['account']->account_no_new }}</td>
                                <td>{{ $item['account']->account_name }}</td>
                                <td>{{ $item['account']->account_type ?: '-' }}</td>
                                <td class="text-end">{{ $item['debit'] > 0 ? number_format($item['debit'], 2, ',', '.') : '-' }}</td>
                                <td class="text-end">{{ $item['credit'] > 0 ? number_format($item['credit'], 2, ',', '.') : '-' }}</td>
                                <td class="text-end">
                                    <span class="{{ $item['balance'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format(abs($item['balance']), 2, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('ledger.show', $item['account']->account_no_new) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">Belum ada data jurnal.</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="table-dark">
                            <th colspan="3">Total</th>
                            <th class="text-end">{{ number_format($totalDebit, 2, ',', '.') }}</th>
                            <th class="text-end">{{ number_format($totalCredit, 2, ',', '.') }}</th>
                            <th class="text-end">
                                <span class="{{ $totalDebit - $totalCredit >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format(abs($totalDebit - $totalCredit), 2, ',', '.') }}
                                </span>
                            </th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
